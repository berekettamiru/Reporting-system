<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\ReportItem;
use App\Models\User;

class ReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $reports = Report::with('items')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $totalReports  = $reports->count();
        $interested    = 0;
        $notInterested = 0;

        foreach ($reports as $report) {
            foreach ($report->items as $item) {
                if ($item->status === 'interested')     $interested++;
                if ($item->status === 'not_interested') $notInterested++;
            }
        }

        return view('dashboard', [
            'totalReports'   => $totalReports,
            'interested'     => $interested,
            'notInterested'  => $notInterested,

            // USER-SPECIFIC COUNTS
            'myReports'      => Report::where('user_id', auth()->id())->count(),
            'allReports'     => Report::count(),

            // 🔥 FIXED: only current user data
            'totalVisits'    => ReportItem::mine()->count(),

            // FOLLOWUPS (USER ONLY)
            'followupsDue'   => ReportItem::mine()
                ->whereBetween('next_follow_up_date', [now(), now()->addDays(30)])
                ->count(),

            'topUsers'       => User::withCount('reports')
                ->orderByDesc('reports_count')
                ->limit(4)
                ->get(),

            'reportsByType'  => Report::selectRaw('report_type, count(*) as count')
                ->groupBy('report_type')
                ->get(),

            'interestHigh'   => ReportItem::mine()
                ->where('interest_level', 'high')
                ->count(),

            'interestMedium' => ReportItem::mine()
                ->where('interest_level', 'medium')
                ->count(),

            'interestLow'    => ReportItem::mine()
                ->where('interest_level', 'low')
                ->count(),

            // 🔥 FIXED UPCOMING FOLLOWUPS
            'upcomingFollowups' => ReportItem::mine()
                ->whereNotNull('next_follow_up_date')
                ->where('next_follow_up_date', '>=', now())
                ->orderBy('next_follow_up_date')
                ->limit(4)
                ->get(),

            'recentReports'  => Report::with('user')
                ->where('user_id', auth()->id())
                ->latest('report_date')
                ->limit(4)
                ->get(),

            'unreadFeedback' => Report::where('user_id', auth()->id())
                ->whereNotNull('feedback')
                ->where('seen', false)
                ->get(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | REPORT LIST
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
{
    $reports = Report::with(['user', 'items'])

        // SEARCH
        ->when($request->search, function ($query) use ($request) {

            $query->where('report_type', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });

        })

        // FROM DATE
        ->when($request->from_date, function ($query) use ($request) {

            $query->whereDate('created_at', '>=', $request->from_date);

        })

        // TO DATE
        ->when($request->to_date, function ($query) use ($request) {

            $query->whereDate('created_at', '<=', $request->to_date);

        })

        ->latest()
        ->get();

    return view('reports.index', compact('reports'));
}

    /*
    |--------------------------------------------------------------------------
    | SHOW CREATE FORM
    |--------------------------------------------------------------------------
    */

    public function create(Request $request)
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect('/reports');
        }

        $query = Report::with('items')
            ->where('user_id', auth()->id());

        if ($request->filled('search')) {
            $query->where('report_type', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('report_type', $request->type);
        }

        $myReports = $query->latest()
            ->paginate(10)
            ->appends($request->query());

        return view('reports.create', compact('myReports'));
    }

    /*
    |--------------------------------------------------------------------------
    | MY REPORTS
    |--------------------------------------------------------------------------
    */

    public function myreports()
    {
        if (auth()->user()->isAdmin()) {
            return redirect('/reports');
        }

        $reports = Report::with('items')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('reports.my', compact('reports'));
    }
//feedback
public function feedbacks()
{
    $feedbacks = Report::where('user_id', auth()->id())
        ->whereNotNull('feedback')
        ->orderBy('id', 'desc')
        ->get();

    return view('reports.feedbacks', compact('feedbacks'));
}
    /*
    |--------------------------------------------------------------------------
    | STORE REPORT
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'report_date'           => 'required|date',
            'report_type'           => 'required',
            'items.*.business_name' => 'required',
        ]);

        $report = Report::create([
            'user_id'     => auth()->id(),
            'report_date' => $request->report_date,
            'report_type' => $request->report_type,
        ]);

        if ($request->has('items')) {
            foreach ($request->items as $item) {

                if (
                    empty($item['business_name']) &&
                    empty($item['location']) &&
                    empty($item['contact_name'])
                ) {
                    continue;
                }

                $report->items()->create([
                    'business_name'       => $item['business_name'] ?? null,
                    'location'            => $item['location'] ?? null,
                    'specific_location'   => $item['specific_location'] ?? null,
                    'contact_name'        => $item['contact_name'] ?? null,
                    'phone'               => $item['phone'] ?? null,
                    'contact_method'      => $item['contact_method'] ?? null,
                    'status'              => $item['status'] ?? null,
                    'interaction_result'  => $item['interaction_result'] ?? null,
                    'interest_level'      => $item['interest_level'] ?? null,
                    'commitment'          => $item['commitment'] ?? null,
                    'next_action'         => $item['next_action'] ?? null,
                    'next_follow_up_date' => $item['next_follow_up_date'] ?? null,
                    'remark'              => $item['remark'] ?? null,
                ]);
            }
        }

        return redirect('/reports/create')
            ->with('success', 'Report created successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Edit report
    |--------------------------------------------------------------------------
    */
    public function edit($id)
{
    $report = Report::with('items')->findOrFail($id);
    return view('reports.edit', compact('report'));
}

public function update(Request $request, $id)
{
    $report = Report::findOrFail($id);

    $report->report_date = $request->report_date ?? $report->report_date;
    $report->report_type = $request->report_type ?? $report->report_type;
    $report->save();

    if ($request->has('items')) {
        foreach ($request->items as $i => $itemData) {
            $item = $report->items[$i] ?? null;
            if ($item) {
                $item->update([
                    'business_name'       => $itemData['business_name']       ?? $item->business_name,
                    'location'            => $itemData['location']            ?? $item->location,
                    'contact_name'        => $itemData['contact_name']        ?? $item->contact_name,
                    'phone'               => $itemData['phone']               ?? $item->phone,
                    'contact_method'      => $itemData['contact_method']      ?? $item->contact_method,
                    'status'              => $itemData['status']              ?? $item->status,
                    'interest_level'      => $itemData['interest_level']      ?? $item->interest_level,
                    'next_action'         => $itemData['next_action']         ?? $item->next_action,
                    'next_follow_up_date' => $itemData['next_follow_up_date'] ?? $item->next_follow_up_date,
                    'remark'              => $itemData['remark']              ?? $item->remark,
                ]);
            }
        }
    }

    return redirect('/reports/' . $report->id)->with('success', 'Report updated successfully.');
}

public function destroy(Request $request, $id)
{
    $report = Report::findOrFail($id);
    $report->items()->delete();
    $report->delete();

    $redirect = $request->redirect_to ?? 'create';

    return match($redirect) {
        'my'  => redirect('/reports/my')->with('success', 'Report deleted.'),
        'all' => redirect('/reports')->with('success', 'Report deleted.'),
        default => redirect('/reports/create')->with('success', 'Report deleted.'),
    };
}

    /*
    |--------------------------------------------------------------------------
    | SHOW SINGLE REPORT
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $report = Report::with('items', 'user')->findOrFail($id);

        // Mark as seen if owner is viewing
        if (auth()->id() === $report->user_id && !$report->seen) {
            $report->update(['seen' => true]);
        }

        return view('reports.show', compact('report'));
    }

    public function updateFeedback(Request $request, $id)
    {
        $request->validate([
            'feedback' => 'nullable|string'
        ]);

        $report = Report::findOrFail($id);
        $report->update([
            'feedback' => $request->feedback,
            'seen' => false // Reset seen so user gets notified
        ]);

        return back()->with('success', 'Feedback updated successfully.');
    }
}