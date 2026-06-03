@extends('layouts.app-dashboard')

@section('title', 'All reports')
@section('content')

<div class="max-w-6xl mx-auto p-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-700">All Reports</h2>
            <p class="text-gray-400 text-sm mt-1">Manage all field reports in one place</p>
        </div>
        <div class="flex items-center gap-2">
            <form method="GET" action="/reports" class="flex gap-2 items-center">

    {{-- Search --}}
    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Search by type or user..."
        class="border border-gray-200 text-sm px-3 py-2 rounded-lg w-56 focus:outline-none focus:ring-1 focus:ring-blue-300">

    {{-- From Date --}}
    <input
        type="date"
        name="from_date"
        value="{{ request('from_date') }}"
        class="border border-gray-200 text-sm px-3 py-2 rounded-lg">

    {{-- To Date --}}
    <input
        type="date"
        name="to_date"
        value="{{ request('to_date') }}"
        class="border border-gray-200 text-sm px-3 py-2 rounded-lg">

    {{-- Button --}}
    <button class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm px-4 py-2 rounded-lg">
        Filter
    </button>

</form>

        </div>
    </div>

    {{-- METRIC CARDS --}}
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-xs text-gray-400 mb-1">Total Reports</p>
            <p class="text-2xl font-semibold text-gray-700">{{ $reports->count() }}</p>
        </div>
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-xs text-gray-400 mb-1">Total Visits</p>
            <p class="text-2xl font-semibold text-gray-700">{{ $reports->sum(fn($r) => $r->items->count()) }}</p>
        </div>
    </div>

    {{-- TABLE --}}
    @if($reports->count() > 0)

    <div class="bg-white border border-gray-100 rounded-lg overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3 text-xs font-medium text-gray-400 uppercase tracking-wide">Reported By</th>
                    <th class="px-5 py-3 text-xs font-medium text-gray-400 uppercase tracking-wide">Type</th>
                    <th class="px-5 py-3 text-xs font-medium text-gray-400 uppercase tracking-wide">Visits</th>
                    <th class="px-5 py-3 text-xs font-medium text-gray-400 uppercase tracking-wide text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($reports as $report)
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 font-semibold text-xs flex-shrink-0">
                                {{ strtoupper(substr($report->user->name ?? '?', 0, 2)) }}
                            </div>
                            <span class="text-sm text-gray-700 font-medium">
                                {{ $report->user->name ?? 'Unknown' }}
                            </span>
                        </div>
                    </td>

                    <td class="px-5 py-3">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium
                            {{ $report->report_type === 'bank'   ? 'bg-blue-50 text-blue-600'   : '' }}
                            {{ $report->report_type === 'school' ? 'bg-green-50 text-green-600' : '' }}
                            {{ $report->report_type === 'cafe'   ? 'bg-yellow-50 text-yellow-600' : '' }}">
                            {{ ucfirst($report->report_type) }}
                        </span>
                    </td>

                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <div class="flex -space-x-1">
                                @for($v = 0; $v < min($report->items->count(), 5); $v++)
                                <div class="w-2 h-2 rounded-full bg-blue-300 border border-white"></div>
                                @endfor
                            </div>
                            <span class="text-sm text-gray-500">{{ $report->items->count() }} visits</span>
                        </div>
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

    @else

    {{-- EMPTY STATE --}}
    <div class="text-center py-20 bg-white rounded-lg border border-gray-100">
        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <p class="text-gray-600 font-medium mb-1">No reports yet</p>
        <p class="text-gray-400 text-sm mb-5">Start by creating your first field report</p>
        @if(!auth()->user()->isAdmin())
        <a href="/reports/create"
           class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-5 py-2 rounded-lg">
            + Create First Report
        </a>
        @endif
    </div>

    @endif

</div>

@endsection