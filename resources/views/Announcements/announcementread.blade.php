@extends('layouts.app-dashboard')

@section('title', 'Announcements')

@section('content')

<div class="max-w-3xl mx-auto p-6">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-700">Announcements</h2>
            <p class="text-sm text-gray-400 mt-1">Latest updates from the team</p>
        </div>
        @if(auth()->user()->isAdmin())
        <a href="/admin/announcements/create"
            class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-4 py-2 rounded-lg">
            + New Announcement
        </a>
        @endif
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-600 rounded-lg p-3 mb-4 text-sm">
        {{ session('success') }}
    </div>
    @endif

    <div class="space-y-4">
        @forelse ($announcements as $announcement)
        @php $seen = $announcement->isReadBy(auth()->id()); @endphp
        <div class="bg-white border rounded-lg p-5 {{ $seen ? 'border-gray-100' : 'border-blue-200' }}">

            {{-- TOP ROW --}}
            <div class="flex items-center gap-2 mb-3">
                <span class="text-xs px-2.5 py-1 rounded-full font-medium
                    {{ $announcement->category === 'general'   ? 'bg-blue-50 text-blue-600'     : '' }}
                    {{ $announcement->category === 'reminder'  ? 'bg-yellow-50 text-yellow-600' : '' }}
                    {{ $announcement->category === 'update'    ? 'bg-green-50 text-green-600'   : '' }}
                    {{ $announcement->category === 'important' ? 'bg-red-50 text-red-500'       : '' }}">
                    {{ ucfirst($announcement->category) }}
                </span>
                <span class="text-xs text-gray-400">{{ $announcement->created_at->format('M j, Y') }}</span>
                <span class="text-gray-300 text-xs">·</span>
                <div class="w-5 h-5 rounded-full bg-blue-50 text-blue-500 text-xs font-semibold flex items-center justify-center flex-shrink-0">
                    {{ strtoupper(substr($announcement->user->name ?? '?', 0, 2)) }}
                </div>
                <span class="text-xs text-gray-500 font-medium">{{ $announcement->user->name ?? 'Unknown' }}</span>

                {{-- UNREAD DOT --}}
                @if(!$seen)
                <span class="ml-auto w-2 h-2 rounded-full bg-blue-400 flex-shrink-0"></span>
                @endif
            </div>

            {{-- TITLE --}}
            <h3 class="text-sm font-semibold text-gray-700 mb-1">{{ $announcement->title }}</h3>

            {{-- BODY --}}
            <p class="text-sm text-gray-500 leading-relaxed mb-4">{{ $announcement->body }}</p>

            {{-- SEEN BUTTON --}}
            <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                @if($seen)
                    <span class="text-xs text-green-500 flex items-center gap-1">
                        ✓ Seen
                    </span>
                @else
                    <form method="POST" action="/announcements/{{ $announcement->id }}/seen">
                        @csrf
                        <button type="submit"
                            class="text-xs bg-blue-50 hover:bg-blue-100 text-blue-600 px-3 py-1.5 rounded-lg font-medium">
                            Mark as seen
                        </button>
                    </form>
                @endif
            </div>

        </div>
        @empty
        <div class="text-center py-12 bg-white rounded-lg border border-gray-100">
            <p class="text-sm text-gray-400">No announcements yet.</p>
        </div>
        @endforelse
    </div>

</div>

@endsection