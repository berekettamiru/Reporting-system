@extends('layouts.app-dashboard')

@section('title', 'Admin Dashboard')

@section('content')

<div class="max-w-6xl mx-auto p-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-700">Admin Dashboard</h2>
            <p class="text-sm text-gray-400 mt-1">{{ now()->format('l, j F Y') }}</p>
        </div>
        <a href="/admin/users"
            class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-4 py-2 rounded-lg">
            Manage Users
        </a>
    </div>
    
    

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-xs text-gray-400 mb-1">Total Users</p>
            <p class="text-2xl font-semibold text-gray-700">{{ $usersCount }}</p>
            <p class="text-xs text-gray-400 mt-1">Registered accounts</p>
        </div>
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-xs text-gray-400 mb-1">Total Reports</p>
            <p class="text-2xl font-semibold text-gray-700">{{ $reportsCount }}</p>
            <p class="text-xs text-gray-400 mt-1">All submitted reports</p>
        </div>
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-xs text-gray-400 mb-1">Total Visits</p>
            <p class="text-2xl font-semibold text-gray-700">{{ $visitsCount }}</p>
            <p class="text-xs text-gray-400 mt-1">Across all reports</p>
        </div>
    </div>
     {{-- TOP PERFORMERS -- --}}
       <div class="bg-white border border-gray-100 rounded-lg p-4">
            <h3 class="text-sm font-semibold text-gray-600 mb-3">Top performers Based on amount of reports</h3>
            @forelse ($topUsers as $user)
            <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">
                
                <span class="text-sm text-gray-600 flex-1">{{ $user->name }}</span>
                <div class="flex-1 bg-gray-100 rounded h-1.5 mx-2">
                    <div class="bg-blue-400 h-1.5 rounded"
                        style="width: {{ $topUsers->first()->reports_count > 0 ? ($user->reports_count / $topUsers->first()->reports_count) * 100 : 0 }}%">
                    </div>
                </div>
                <span class="text-sm font-medium text-gray-700">{{ $user->reports_count }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-400">No data yet.</p>
            @endforelse
        </div>


    {{-- RECENT REPORTS --}}
    <div class="bg-white border border-gray-100 rounded-lg overflow-hidden">

        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-600">Recent Reports</h3>
            <a href="/reports" class="text-xs text-blue-500 hover:text-blue-700">View all →</a>
        </div>

        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-gray-50">
                    <th class="px-5 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide">Reported By</th>
                    <th class="px-5 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide">Type</th>
                    <th class="px-5 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide">Date</th>
                    <th class="px-5 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($recentReports as $report)
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-full bg-blue-50 text-blue-500 text-xs font-semibold flex items-center justify-center flex-shrink-0">
                                {{ strtoupper(substr($report->user->name ?? '?', 0, 2)) }}
                            </div>
                            <span class="text-sm text-gray-700">{{ $report->user->name ?? 'Unknown' }}</span>
                        </div>
                    </td>

                    <td class="px-5 py-3">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium
                            {{ $report->report_type === 'bank'   ? 'bg-blue-50 text-blue-600'     : '' }}
                            {{ $report->report_type === 'school' ? 'bg-green-50 text-green-600'   : '' }}
                            {{ $report->report_type === 'cafe'   ? 'bg-yellow-50 text-yellow-600' : '' }}">
                            {{ ucfirst($report->report_type) }}
                        </span>
                    </td>

                    <td class="px-5 py-3 text-sm text-gray-400">
                        {{ \Carbon\Carbon::parse($report->report_date)->format('M j, Y') }}
                    </td>

                    <td class="px-5 py-3 text-right">
                        <a href="/reports/{{ $report->id }}"
                           class="text-sm text-blue-500 hover:text-blue-700 font-medium">
                            View →
                        </a>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</div>

@endsection