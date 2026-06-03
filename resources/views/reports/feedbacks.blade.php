@extends('layouts.app-dashboard')

@section('title', 'Feedbacks')

@section('content')

<div class="bg-white border border-gray-200 rounded-lg overflow-hidden">

    <div class="px-5 py-4 border-b">
        <h2 class="font-semibold text-gray-800">
            All Feedbacks
        </h2>
    </div>

    @forelse($feedbacks as $report)

    <div class="p-5 border-b">

        <div class="flex justify-between items-center mb-2">

            <div>
                <p class="text-sm font-medium text-gray-700">
                    Report #{{ $report->id }}
                </p>

                <p class="text-xs text-gray-400">
                    {{ $report->report_date }}
                </p>
            </div>

            <a href="/reports/{{ $report->id }}"
               class="text-blue-500 text-sm hover:underline">
                Open
            </a>

        </div>

        <div class="bg-amber-50 border border-amber-200 rounded p-3">

            <p class="text-sm text-gray-700">
                {{ $report->feedback }}
            </p>

        </div>

    </div>

    @empty

    <div class="p-10 text-center text-gray-400">
        No feedback available.
    </div>

    @endforelse

</div>

@endsection