@extends('layouts.app-dashboard')

@section('title', 'Announcements')

@section('content')

<div class="max-w-3xl mx-auto p-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-700">Announcements</h2>
            <p class="text-sm text-gray-400 mt-1">Latest updates from the team</p>
        </div>
        <a href="/admin/announcements/create"
            class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-4 py-2 rounded-lg">
            + New Announcement
        </a>
    </div>

    {{-- LIST --}}
    <div class="space-y-4">
        @forelse ($announcements as $announcement)

        <div class="bg-white border border-gray-100 rounded-lg p-5">

            {{-- TOP ROW --}}
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="text-xs px-2.5 py-1 rounded-full font-medium
                        {{ $announcement->category === 'general'   ? 'bg-blue-50 text-blue-600'     : '' }}
                        {{ $announcement->category === 'reminder'  ? 'bg-yellow-50 text-yellow-600' : '' }}
                        {{ $announcement->category === 'update'    ? 'bg-green-50 text-green-600'   : '' }}
                        {{ $announcement->category === 'important' ? 'bg-red-50 text-red-500'       : '' }}">
                        {{ ucfirst($announcement->category) }}
                    </span>
                    <span class="text-xs text-gray-400">
                        {{ $announcement->created_at->format('M j, Y') }}
                    </span>
                    <span class="text-gray-300 text-xs">·</span>
                    <div class="w-5 h-5 rounded-full bg-blue-50 text-blue-500 text-xs font-semibold flex items-center justify-center flex-shrink-0">
                        {{ strtoupper(substr($announcement->user->name ?? '?', 0, 2)) }}
                    </div>
                    <span class="text-xs text-gray-500">{{ $announcement->user->name ?? 'Unknown' }}</span>
                </div>
                <a href="/admin/announcements/{{ $announcement->id }}/reads"
                   class="text-xs bg-green-50 text-green-600 hover:bg-green-100 px-3 py-1 rounded-full font-medium">
                    {{ $announcement->reads->count() }} seen →
                </a>
            </div>

            {{-- TITLE --}}
            <h3 class="text-sm font-semibold text-gray-700 mb-1">{{ $announcement->title }}</h3>

            {{-- BODY --}}
            <p class="text-sm text-gray-500 leading-relaxed">{{ $announcement->body }}</p>

        </div>

        @empty

        <div class="text-center py-12 bg-white rounded-lg border border-gray-100">
            <p class="text-sm text-gray-400">No announcements yet.</p>
        </div>

        @endforelse
    </div>

</div>

@endsection