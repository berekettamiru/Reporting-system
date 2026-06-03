@extends('layouts.app-dashboard')

@section('title', 'Edit Report')

@section('content')

@if ($errors->any())
<div class="bg-red-50 border border-red-200 text-red-600 rounded-lg p-3 mb-4 text-xs">
    <ul class="list-disc list-inside space-y-0.5">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="max-w-4xl mx-auto p-6">

    <div class="mb-5">
        <h2 class="text-lg font-semibold text-gray-700">Edit Report</h2>
        <p class="text-xs text-gray-400 mt-1">Update the report details below</p>
    </div>

    <form method="POST" action="/reports/{{ $report->id }}">
        @csrf
        @method('PATCH')

        {{-- REPORT INFO --}}
        <div class="bg-white border border-gray-100 rounded-lg p-4 mb-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Report Info</p>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Date</label>
                    <input type="date" name="report_date"
                        value="{{ old('report_date', $report->report_date) }}"
                        class="border border-gray-300 rounded-lg p-2 w-full text-xs focus:outline-none focus:border-blue-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Type</label>
                    <select name="report_type"
                        class="border border-gray-300 rounded-lg p-2 w-full text-xs focus:outline-none focus:border-blue-400">
                        @foreach(['bank','school','cafe','sacco','clinic'] as $type)
                        <option value="{{ $type }}"
                            {{ old('report_type', $report->report_type) === $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- VISITS --}}
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Visits</p>

        <div id="rows-container" class="space-y-3 mb-4">
            @foreach($report->items as $i => $item)
            <div class="bg-white border border-gray-100 rounded-lg p-4">

                <div class="flex items-center justify-between mb-3 pb-2 border-b border-gray-100">
                    <span class="text-xs font-semibold text-gray-600">Visit #{{ $i + 1 }}</span>
                </div>

                {{-- CONTACT --}}
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Contact</p>
                <div class="grid grid-cols-2 gap-2 mb-3">
                    <input name="items[{{ $i }}][business_name]"
                        value="{{ old("items.$i.business_name", $item->business_name) }}"
                        placeholder="Business name"
                        class="border border-gray-200 rounded p-1.5 w-full text-xs focus:outline-none focus:border-blue-400">
                    <input name="items[{{ $i }}][location]"
                        value="{{ old("items.$i.location", $item->location) }}"
                        placeholder="Location"
                        class="border border-gray-200 rounded p-1.5 w-full text-xs focus:outline-none focus:border-blue-400">
                    <input name="items[{{ $i }}][contact_name]"
                        value="{{ old("items.$i.contact_name", $item->contact_name) }}"
                        placeholder="Contact name"
                        class="border border-gray-200 rounded p-1.5 w-full text-xs focus:outline-none focus:border-blue-400">
                    <input name="items[{{ $i }}][phone]"
                        value="{{ old("items.$i.phone", $item->phone) }}"
                        placeholder="Phone number"
                        class="border border-gray-200 rounded p-1.5 w-full text-xs focus:outline-none focus:border-blue-400">
                </div>

                <hr class="border-gray-100 my-2">

                {{-- OUTCOME --}}
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Outcome</p>
                <div class="grid grid-cols-3 gap-2 mb-3">
                    <div>
                        <select name="items[{{ $i }}][contact_method]"
                            class="border border-gray-200 rounded p-1.5 w-full text-xs focus:outline-none focus:border-blue-400">
                            <option value="">Method</option>
                            <option value="call"  {{ old("items.$i.contact_method", $item->contact_method) === 'call'  ? 'selected' : '' }}>Call</option>
                            <option value="visit" {{ old("items.$i.contact_method", $item->contact_method) === 'visit' ? 'selected' : '' }}>Visit</option>
                        </select>
                    </div>
                    <div>
                        <select name="items[{{ $i }}][status]"
                            class="border border-gray-200 rounded p-1.5 w-full text-xs focus:outline-none focus:border-blue-400">
                            <option value="">Status</option>
                            <option value="interested"     {{ old("items.$i.status", $item->status) === 'interested'     ? 'selected' : '' }}>Interested</option>
                            <option value="not_interested" {{ old("items.$i.status", $item->status) === 'not_interested' ? 'selected' : '' }}>Not Interested</option>
                            <option value="follow_up"      {{ old("items.$i.status", $item->status) === 'follow_up'      ? 'selected' : '' }}>Follow Up</option>
                        </select>
                    </div>
                    <div>
                        <select name="items[{{ $i }}][interest_level]"
                            class="border border-gray-200 rounded p-1.5 w-full text-xs focus:outline-none focus:border-blue-400">
                            <option value="">Interest</option>
                            <option value="low"    {{ old("items.$i.interest_level", $item->interest_level) === 'low'    ? 'selected' : '' }}>↓ Low</option>
                            <option value="medium" {{ old("items.$i.interest_level", $item->interest_level) === 'medium' ? 'selected' : '' }}>— Medium</option>
                            <option value="high"   {{ old("items.$i.interest_level", $item->interest_level) === 'high'   ? 'selected' : '' }}>↑ High</option>
                        </select>
                    </div>
                </div>

                <hr class="border-gray-100 my-2">

                {{-- FOLLOW-UP --}}
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Follow-up</p>
                <div class="grid grid-cols-2 gap-2">
                    <input name="items[{{ $i }}][next_action]"
                        value="{{ old("items.$i.next_action", $item->next_action) }}"
                        placeholder="Next action"
                        class="border border-gray-200 rounded p-1.5 w-full text-xs focus:outline-none focus:border-blue-400">
                    <input type="date" name="items[{{ $i }}][next_follow_up_date]"
                        value="{{ old("items.$i.next_follow_up_date", $item->next_follow_up_date) }}"
                        class="border border-gray-200 rounded p-1.5 w-full text-xs focus:outline-none focus:border-blue-400">
                    <input name="items[{{ $i }}][remark]"
                        value="{{ old("items.$i.remark", $item->remark) }}"
                        placeholder="Remark"
                        class="border border-gray-200 rounded p-1.5 w-full text-xs col-span-2 focus:outline-none focus:border-blue-400">
                </div>

            </div>
            @endforeach
        </div>

        <div class="flex gap-3">
            <button type="submit"
                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium py-2 rounded-lg">
                Update Report
            </button>
            <a href="/reports/{{ $report->id }}"
                class="flex-1 text-center border border-gray-200 text-gray-500 hover:bg-gray-50 text-sm py-2 rounded-lg">
                Cancel
            </a>
        </div>

    </form>
</div>

@endsection