@extends('layouts.app-dashboard')

@section('title', 'My Reports')

@section('content')

<div class="max-w-6xl mx-auto p-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-700">My Reports</h2>
            <p class="text-sm text-gray-400 mt-1">All reports submitted by you</p>
        </div>
        <a href="/reports/create"
           class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-4 py-2 rounded-lg font-medium">
            + New Report
        </a>
    </div>

    {{-- SUMMARY --}}
    <div class="grid grid-cols-4 gap-3 mb-6">
        <div class="bg-gray-50 rounded-lg px-4 py-3">
            <p class="text-xs text-gray-400">Total Reports</p>
            <p class="text-xl font-semibold text-gray-700">{{ $reports->count() }}</p>
        </div>
        <div class="bg-blue-50 rounded-lg px-4 py-3">
            <p class="text-xs text-blue-400">Total Visits</p>
            <p class="text-xl font-semibold text-blue-600">{{ $reports->sum(fn($r) => $r->items->count()) }}</p>
        </div>
        <div class="bg-green-50 rounded-lg px-4 py-3">
            <p class="text-xs text-green-500">Interested</p>
            <p class="text-xl font-semibold text-green-600">
                {{ $reports->sum(fn($r) => $r->items->where('status', 'interested')->count()) }}
            </p>
        </div>
        <div class="bg-yellow-50 rounded-lg px-4 py-3">
            <p class="text-xs text-yellow-500">Follow Ups</p>
            <p class="text-xl font-semibold text-yellow-600">
                {{ $reports->sum(fn($r) => $r->items->where('status', 'follow_up')->count()) }}
            </p>
        </div>
    </div>

    {{-- TABLE --}}
    @if($reports->count() > 0)

    <div class="bg-white border border-gray-100 rounded-lg overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide">Date</th>
                    <th class="px-5 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide">Type</th>
                    <th class="px-5 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide">Visits</th>
                    <th class="px-5 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide">Interested</th>
                    <th class="px-5 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide">Follow Up</th>
                    <th class="px-5 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($reports as $report)
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-5 py-3 text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($report->report_date)->format('M j, Y') }}
                    </td>

                    <td class="px-5 py-3">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium
                            {{ $report->report_type === 'bank'   ? 'bg-blue-50 text-blue-600'     : '' }}
                            {{ $report->report_type === 'school' ? 'bg-green-50 text-green-600'   : '' }}
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
                            <span class="text-sm text-gray-500">{{ $report->items->count() }}</span>
                        </div>
                    </td>

                    <td class="px-5 py-3">
                        <span class="text-sm font-medium text-green-600">
                            {{ $report->items->where('status', 'interested')->count() }}
                        </span>
                    </td>

                    <td class="px-5 py-3">
                        <span class="text-sm font-medium text-yellow-600">
                            {{ $report->items->where('status', 'follow_up')->count() }}
                        </span>
                    </td>

                 <td class="px-5 py-2.5 text-right">
    <div class="flex items-center justify-end gap-1">

        {{-- VIEW --}}
        <a href="/reports/{{ $report->id }}"
           class="p-1.5 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-500 hover:text-gray-700"
           title="View">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
        </a>

        {{-- EDIT --}}
        <a href="/reports/{{ $report->id }}/edit"
           class="p-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-500 hover:text-blue-700"
           title="Edit">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
        </a>

        {{-- DELETE --}}
        <form method="POST" action="/reports/{{ $report->id }}"
              onsubmit="return confirm('Delete this report?')">
            @csrf
            @method('DELETE')
            <input type="hidden" name="redirect_to" value="my">
            <button type="submit"
                class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-400 hover:text-red-600"
                title="Delete">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </form>

    </div>
</td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @else

    {{-- EMPTY STATE --}}
    <div class="text-center py-16 bg-white rounded-lg border border-gray-100">
        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <p class="text-gray-600 font-medium mb-1">No reports yet</p>
        <p class="text-gray-400 text-sm mb-4">Start by creating your first field report</p>
        <a href="/reports/create"
           class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-5 py-2 rounded-lg">
            + Create First Report
        </a>
    </div>

    @endif

</div>

@endsection