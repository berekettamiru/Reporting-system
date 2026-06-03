@extends('layouts.app-dashboard')

@section('title', 'Who Seen This')

@section('content')

<div class="max-w-3xl mx-auto p-6">

    {{-- HEADER --}}
    <div class="mb-6">
        <a href="/admin/announcements" class="text-xs text-gray-400 hover:text-gray-600">← Back</a>
        <h2 class="text-xl font-semibold text-gray-700 mt-0.5">Who seen this</h2>
        <p class="text-sm text-gray-400 mt-1">{{ $announcement->title }}</p>
    </div>

    {{-- SUMMARY --}}
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="bg-green-50 rounded-lg px-4 py-3">
            <p class="text-xs text-green-500 mb-1">Seen</p>
            <p class="text-2xl font-semibold text-green-600">{{ $announcement->reads->count() }}</p>
        </div>
        <div class="bg-red-50 rounded-lg px-4 py-3">
            <p class="text-xs text-red-400 mb-1">Not seen</p>
            <p class="text-2xl font-semibold text-red-500">
                {{ \App\Models\User::count() - $announcement->reads->count() }}
            </p>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white border border-gray-100 rounded-lg overflow-hidden">

        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-600">Seen by</h3>
            <span class="text-xs px-2.5 py-1 rounded-full bg-green-50 text-green-600">
                {{ $announcement->reads->count() }} users
            </span>
        </div>

        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-gray-50">
                    <th class="px-5 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide">#</th>
                    <th class="px-5 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide">User</th>
                    <th class="px-5 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide">Role</th>
                    <th class="px-5 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide">Seen at</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($announcement->reads as $index => $read)
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-5 py-3 text-xs text-gray-400">{{ $index + 1 }}</td>

                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            
                            <span class="text-sm text-gray-700">{{ $read->user->name ?? 'Unknown' }}</span>
                        </div>
                    </td>

                    <td class="px-5 py-3">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium
                            {{ $read->user->role === 'admin' ? 'bg-blue-50 text-blue-600' : 'bg-gray-100 text-gray-500' }}">
                            {{ ucfirst($read->user->role ?? '—') }}
                        </span>
                    </td>

                    <td class="px-5 py-3 text-xs text-gray-400">
                        {{ \Carbon\Carbon::parse($read->read_at)->format('M j, Y · g:i A') }}
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-12 text-center text-sm text-gray-400">
                        No one has seen this yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>

</div>

@endsection