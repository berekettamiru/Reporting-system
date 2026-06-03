@extends('layouts.app-dashboard')

@section('title', 'Report Details')

@section('content')

<div class="max-w-6xl mx-auto p-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            {{-- <a href="/reports" class="text-xs text-gray-400 hover:text-gray-600">← Back</a> --}}
            <h2 class="text-lg font-semibold text-gray-700 mt-0.5">Report Details</h2>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs px-2.5 py-1 rounded-full font-medium
                {{ $report->report_type === 'bank'   ? 'bg-blue-50 text-blue-600'     : '' }}
                {{ $report->report_type === 'school' ? 'bg-green-50 text-green-600'   : '' }}
                {{ $report->report_type === 'cafe'   ? 'bg-yellow-50 text-yellow-600' : '' }}"
                 {{ $report->report_type === 'sacco'   ? 'bg-yellow-50 text-yellow-600' : '' }}"
                 {{ $report->report_type === 'clinic'   ? 'bg-yellow-50 text-yellow-600' : '' }}">
                 
                {{ ucfirst($report->report_type) }}
            </span>
            <span class="text-xs px-2.5 py-1 rounded-full bg-gray-100 text-gray-500">
                {{ \Carbon\Carbon::parse($report->report_date)->format('M j, Y') }}
            </span>
            <span class="text-xs px-2.5 py-1 rounded-full bg-gray-100 text-gray-500">
                {{ $report->user->name ?? 'Unknown' }}
            </span>
        </div>
    </div>

    {{-- SUMMARY --}}
    <div class="grid grid-cols-4 gap-3 mb-4">
        <div class="bg-gray-50 rounded-lg px-4 py-3">
            <p class="text-xs text-gray-400">Total Visits</p>
            <p class="text-xl font-semibold text-gray-700">{{ $report->items->count() }}</p>
        </div>
        <div class="bg-green-50 rounded-lg px-4 py-3">
            <p class="text-xs text-green-500">Interested</p>
            <p class="text-xl font-semibold text-green-600">{{ $report->items->where('status', 'interested')->count() }}</p>
        </div>
        <div class="bg-red-50 rounded-lg px-4 py-3">
            <p class="text-xs text-red-400">Not Interested</p>
            <p class="text-xl font-semibold text-red-500">{{ $report->items->where('status', 'not_interested')->count() }}</p>
        </div>
        <div class="bg-yellow-50 rounded-lg px-4 py-3">
            <p class="text-xs text-yellow-500">Follow Up</p>
            <p class="text-xl font-semibold text-yellow-600">{{ $report->items->where('status', 'follow_up')->count() }}</p>
        </div>
    </div>
    
    {{-- FEEDBACK SECTION --}}
    @if($report->feedback || auth()->user()->isAdmin())
    <div class="mb-6">
        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
            <h3 class="text-sm font-semibold text-blue-700 mb-2 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1-10a9 9 0 11-9 9 9 9 0 019-9z" />
                </svg>
                Manager Feedback
            </h3>
            
            @if($report->feedback)
                <p class="text-sm text-blue-800 leading-relaxed">
                    {{ $report->feedback }}
                </p>
            @else
                <p class="text-sm text-blue-400 italic">No feedback provided yet.</p>
            @endif

            @if(auth()->user()->isAdmin())
                <form action="/admin/reports/{{ $report->id }}/feedback" method="POST" class="mt-4 pt-4 border-t border-blue-100">
                    @csrf
                    <label class="block text-xs font-medium text-blue-600 mb-1 uppercase">Update Feedback</label>
                    <textarea 
                        name="feedback" 
                        rows="3" 
                        class="w-full text-sm border-blue-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white/50"
                        placeholder="Write your feedback here..."
                    >{{ $report->feedback }}</textarea>
                    <div class="flex justify-end mt-2">
                        <button type="submit" class="bg-blue-600 text-white text-xs px-3 py-1.5 rounded-md hover:bg-blue-700 transition">
                            Save Feedback
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
    @endif

    {{-- VISITS TABLE --}}
    @if($report->items->count() > 0)
    <div class="bg-white border border-gray-100 rounded-lg overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="px-4 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide">#</th>
                    <th class="px-4 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide">Business</th>
                    <th class="px-4 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide">Contact</th>
                    <th class="px-4 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide">Method</th>
                    <th class="px-4 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide">Status</th>
                    <th class="px-4 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide">Interest</th>
                    <th class="px-4 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide">Next Action</th>
                    <th class="px-4 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide">Follow-up</th>
                    <th class="px-4 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide">Remark</th>
                    
                </tr>
            </thead>
            
            <tbody class="divide-y divide-gray-50">
                @foreach($report->items as $index => $item)
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-4 py-3 text-xs text-gray-400">{{ $index + 1 }}</td>

                    <td class="px-4 py-3">
                        <p class="text-sm font-medium text-gray-700">{{ $item->business_name }}</p>
                        <p class="text-xs text-gray-400">{{ $item->location }}</p>
                    </td>

                    <td class="px-4 py-3">
                        <p class="text-sm text-gray-600">{{ $item->contact_name ?? '—' }}</p>
                        <p class="text-xs text-gray-400">{{ $item->phone ?? '' }}</p>
                    </td>

                    <td class="px-4 py-3 text-sm text-gray-500 capitalize">
                        {{ $item->contact_method ?? '—' }}
                    </td>

                    <td class="px-4 py-3">
                        @if($item->status === 'interested')
                            <span class="text-xs px-2 py-0.5 rounded-full bg-green-50 text-green-600">Interested</span>
                        @elseif($item->status === 'not_interested')
                            <span class="text-xs px-2 py-0.5 rounded-full bg-red-50 text-red-500">Not Interested</span>
                        @elseif($item->status === 'follow_up')
                            <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-50 text-yellow-600">Follow Up</span>
                        @else
                            <span class="text-xs text-gray-300">—</span>
                        @endif
                    </td>

                    <td class="px-4 py-3">
                        @if($item->interest_level === 'high')
                            <span class="text-xs px-2 py-0.5 rounded-full bg-green-50 text-green-600">↑ High</span>
                        @elseif($item->interest_level === 'medium')
                            <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-50 text-yellow-600">— Med</span>
                        @elseif($item->interest_level === 'low')
                            <span class="text-xs px-2 py-0.5 rounded-full bg-red-50 text-red-500">↓ Low</span>
                        @else
                            <span class="text-xs text-gray-300">—</span>
                        @endif
                    </td>

                    <td class="px-4 py-3 text-sm text-gray-500">
                        {{ $item->next_action ?? '—' }}
                    </td>

                    <td class="px-4 py-3 text-xs text-gray-400">
                        {{ $item->next_follow_up_date ? \Carbon\Carbon::parse($item->next_follow_up_date)->format('M j, Y') : '—' }}
                    </td>

                    <td class="px-4 py-3 text-xs text-gray-400 italic">
                        {{ $item->remark ?? '—' }}
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @else
    <div class="text-center py-12 bg-white rounded-lg border border-gray-100">
        <p class="text-sm text-gray-400">No visits recorded for this report.</p>
    </div>
    @endif

</div>

@endsection