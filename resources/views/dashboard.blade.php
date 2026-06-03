@extends('layouts.app-dashboard')

@section('content')

<div class="max-w-6xl mx-auto py-6 px-4">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-700">
                Good morning, {{ auth()->user()->name }}
            </h1>

            <p class="text-sm text-gray-400 mt-1">
                {{ now()->format('l, j F Y') }}
            </p>
        </div>

        <a href="/reports/create"
            class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-4 py-2 rounded-lg">
            + New report
        </a>
    </div>
    
    {{-- FEEDBACK NOTIFICATIONS
    @if($unreadFeedback->count() > 0)
    <div class="mb-6">
        @foreach($unreadFeedback as $report)
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-3 flex items-center justify-between shadow-sm animate-pulse">
            <div class="flex items-center gap-3">
                <div class="bg-amber-100 p-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-amber-800">New Management Feedback</h4>
                    <p class="text-xs text-amber-700">You received feedback on your <span class="font-medium">{{ $report->report_type }}</span> report from {{ $report->report_date->format('M j') }}.</p>
                </div>
            </div>
            <a href="/reports/{{ $report->id }}" class="bg-amber-600 text-white text-xs px-3 py-1.5 rounded-lg hover:bg-amber-700 transition">
                View Feedback
            </a>
        </div>
        @endforeach
    </div>
    @endif --}}

    {{-- METRIC CARDS --}}
    <div class="grid grid-cols-4 gap-4 mb-6">

        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-xs text-gray-500 mb-1">My reports</p>
            <p class="text-2xl font-semibold text-gray-700">
                {{ $myReports }}
            </p>
            <p class="text-xs text-gray-400 mt-1">
                This month
            </p>
        </div>

        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-xs text-gray-500 mb-1">Total reports</p>
            <p class="text-2xl font-semibold text-gray-700">
                {{ $allReports }}
            </p>
            <p class="text-xs text-gray-400 mt-1">
                All users
            </p>
        </div>

        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-xs text-gray-500 mb-1">
                Follow-ups due
            </p>

            <p class="text-2xl font-semibold text-red-500">
                {{ $followupsDue }}
            </p>

            <p class="text-xs text-gray-400 mt-1">
                Next 3 days
            </p>
        </div>

    </div>


    <div class="grid grid-cols-2 gap-4">

        {{-- UPCOMING FOLLOWUPS --}}
        <div class="bg-white border border-gray-100 rounded-lg p-4">

            <h3 class="text-sm font-semibold text-gray-600 mb-3">
                Upcoming follow-ups
            </h3>

            @forelse ($upcomingFollowups as $item)

            @php
                $date = \Carbon\Carbon::parse($item->next_follow_up_date);
            @endphp

            <div class="py-2 border-b border-gray-50 last:border-0">

                <a href="/reports/{{ $item->report->id }}"
                   class="flex items-start gap-3 hover:bg-gray-50 p-2 rounded transition">

                    <span class="text-xs px-2 py-0.5 rounded-full flex-shrink-0
                        {{ $date->isToday()
                            ? 'bg-red-50 text-red-500'
                            : ($date->isTomorrow()
                            ? 'bg-yellow-50 text-yellow-600'
                            : 'bg-blue-50 text-blue-500') }}">

                        {{ $date->isToday()
                            ? 'Today'
                            : ($date->isTomorrow()
                            ? 'Tomorrow'
                            : $date->format('M j')) }}

                    </span>

                    <div>
                        <p class="text-sm text-gray-700">
                            {{ $item->business_name }}
                        </p>

                        <p class="text-xs text-gray-400">
                            {{ $item->next_action }}
                        </p>

                        <p class="text-xs text-blue-500 mt-1">
                            View report →
                        </p>
                    </div>

                </a>

            </div>

            @empty

            <p class="text-sm text-gray-400">
                No upcoming follow-ups.
            </p>

            @endforelse

        </div>



        {{-- RECENT REPORTS --}}
        <div class="bg-white border border-gray-100 rounded-lg p-4">

            <h3 class="text-sm font-semibold text-gray-600 mb-3">
                Recent reports
            </h3>

            @forelse ($recentReports as $report)

            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">

                <div>

                    <p class="text-sm text-gray-700 capitalize">
                        {{ $report->report_type }} report
                    </p>

                    <p class="text-xs text-gray-400">
                        {{ \Carbon\Carbon::parse($report->report_date)->format('M j') }}
                        ·
                        {{ $report->user->name ?? 'Unknown' }}
                    </p>

                </div>

                <a href="/reports/{{ $report->id }}"
                   class="text-xs px-2 py-0.5 rounded-full bg-green-50 text-green-600 hover:bg-green-100">

                    View →

                </a>

            </div>

            @empty

            <p class="text-sm text-gray-400">
                No reports yet.
            </p>

            @endforelse

        </div>

    </div>

</div>

@endsection