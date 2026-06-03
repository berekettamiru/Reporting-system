<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
   public function index()
{
    $announcements = Announcement::with(['user', 'reads'])->latest()->get();
    return view('admin.announcements.index', compact('announcements'));
}

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'category' => 'required|string',
            'body'     => 'required|string',
        ]);

        Announcement::create([
            'user_id'  => auth()->id(),
            'title'    => $request->title,
            'category' => $request->category,
            'body'     => $request->body,
        ]);

        return redirect('/admin/announcements')->with('success', 'Announcement posted.');
    }
    
    public function reads($id)
{
    $announcement = Announcement::with('reads.user')->findOrFail($id);
    return view('admin.announcements.reads', compact('announcement'));
}

}