@extends('layouts.app-dashboard')

@section('title', 'Create Report')

@section('content')

@if ($errors->any())
<div class="bg-red-50 border border-red-200 text-red-600 rounded-lg p-3 mb-4 text-xs">
    <p class="font-semibold mb-1">Please fix the following:</p>
    <ul class="list-disc list-inside space-y-0.5">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="flex h-full border border-gray-200 rounded-lg overflow-hidden bg-white">

    {{-- LEFT: FORM --}}
    <div class="w-80 flex-shrink-0 border-r border-gray-200 p-5 overflow-y-auto">

        <h2 class="text-sm font-bold text-gray-800 mb-4 pb-2 border-b border-gray-200">Report Form</h2>

        <form method="POST" action="/reports">
            @csrf

            {{-- DATE + TYPE IN ONE ROW --}}
            <div class="grid grid-cols-2 gap-2 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="report_date"
                        class="border border-gray-300 rounded p-1.5 w-full text-xs focus:outline-none focus:border-blue-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        Type <span class="text-red-500">*</span>
                    </label>
                    <select name="report_type"
                        class="border border-gray-300 rounded p-1.5 w-full text-xs focus:outline-none focus:border-blue-400">
                        <option value="bank">Bank</option>
                        <option value="school">School</option>
                        <option value="cafe">Cafe</option>
                        <option value="sacco">Sacco</option>
                        <option value="clinic">Clinic</option>
                    </select>
                </div>
            </div>

            {{-- VISITS --}}
            <p class="text-xs font-bold text-gray-700 mb-2">Visits <span class="text-red-500">*</span></p>

            <div id="rows-container" class="space-y-3"></div>

            <button type="button" onclick="addRow()"
                class="mt-3 w-full border border-dashed border-gray-300 text-gray-400 text-xs py-2 rounded hover:bg-gray-50">
                + Add Visit
            </button>

            <button type="submit"
                class="mt-3 w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded text-xs">
                Submit
            </button>

        </form>
    </div>

    {{-- RIGHT: TABLE --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- TABLE HEADER --}}
        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-200">
            <h2 class="text-sm font-bold text-gray-800">My Reports</h2>
            <form method="GET" action="/reports/create" class="flex items-center gap-2">
                <div class="flex items-center border border-gray-300 rounded overflow-hidden">
                    <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search Reports..."
                        class="px-3 py-1.5 text-xs w-48 focus:outline-none">
                    <button type="submit" class="px-2 py-1.5 bg-gray-50 border-l border-gray-300 text-gray-400 hover:text-gray-600">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
                <select name="type"
                    class="border border-gray-300 rounded px-2 py-1.5 text-xs focus:outline-none">
                    <option value="">All types</option>
                    <option value="bank"   {{ request('type') === 'bank'   ? 'selected' : '' }}>Bank</option>
                    <option value="school" {{ request('type') === 'school' ? 'selected' : '' }}>School</option>
                    <option value="cafe"   {{ request('type') === 'cafe'   ? 'selected' : '' }}>Cafe</option>
                    <option value="sacco"  {{ request('type') === 'sacco'  ? 'selected' : '' }}>Sacco</option>
                    <option value="clinic" {{ request('type') === 'clinic' ? 'selected' : '' }}>Clinic</option>
                </select>
                <button type="submit"
    class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1.5 rounded flex items-center gap-1">

    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M3 4a1 1 0 011-1h16a1 1 0 01.8 1.6L14 13v6l-4-2v-4L3.2 5.6A1 1 0 013 4z"/>
    </svg>

    Filter
</button>
                @if(request('search') || request('type'))
                <a href="/reports/create" class="text-xs text-red-400 hover:text-red-600">Clear</a>
                @endif
            </form>
        </div>

        {{-- TABLE --}}
        <div class="flex-1 overflow-y-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 sticky top-0">
                    <tr class="border-b border-gray-200">
                        <th class="px-5 py-3 text-xs font-semibold text-gray-600">#</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-600">Date</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-600">Type</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-600">Business</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-600">Contact</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-600">Phone</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-600">Status</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-600 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($myReports as $index => $report)
                    @php $firstItem = $report->items->first(); @endphp
                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-5 py-2.5 text-xs text-gray-400">{{ $myReports->firstItem() + $index }}</td>

                        <td class="px-5 py-2.5 text-xs text-gray-600">
                            {{ \Carbon\Carbon::parse($report->report_date)->format('M j') }}
                        </td>

                        <td class="px-5 py-2.5">
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                {{ $report->report_type === 'bank'   ? 'bg-blue-50 text-blue-600'     : '' }}
                                {{ $report->report_type === 'school' ? 'bg-green-50 text-green-600'   : '' }}
                                {{ $report->report_type === 'cafe'   ? 'bg-yellow-50 text-yellow-600' : '' }}
                                {{ $report->report_type === 'sacco'  ? 'bg-purple-50 text-purple-600' : '' }}
                                {{ $report->report_type === 'clinic' ? 'bg-red-50 text-red-500'       : '' }}">
                                {{ ucfirst($report->report_type) }}
                            </span>
                        </td>

                        <td class="px-5 py-2.5 text-xs text-gray-600">
                            {{ $firstItem->business_name ?? '—' }}
                        </td>

                        <td class="px-5 py-2.5 text-xs text-gray-600">
                            {{ $firstItem->contact_name ?? '—' }}
                        </td>

                        <td class="px-5 py-2.5 text-xs text-gray-600">
                            {{ $firstItem->phone ?? '—' }}
                        </td>

                        <td class="px-5 py-2.5">
                            @php $status = $firstItem->status ?? null; @endphp
                            @if($status === 'interested')
                                <span class="text-xs px-2 py-0.5 rounded-full bg-green-50 text-green-600">Interested</span>
                            @elseif($status === 'not_interested')
                                <span class="text-xs px-2 py-0.5 rounded-full bg-red-50 text-red-500">Not Interested</span>
                            @elseif($status === 'follow_up')
                                <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-50 text-yellow-600">Follow Up</span>
                            @else
                                <span class="text-xs text-gray-300">—</span>
                            @endif
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
            <input type="hidden" name="redirect_to" value="create">
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
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-10 text-center text-xs text-gray-400">
                            No reports found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($myReports->hasPages())
        <div class="px-5 py-3 border-t border-gray-200 flex items-center justify-between bg-white">
            <span class="text-xs text-gray-400">
                Showing {{ $myReports->firstItem() }}–{{ $myReports->lastItem() }} of {{ $myReports->total() }}
            </span>
            <div class="flex items-center gap-1">
                @if($myReports->onFirstPage())
                    <span class="text-xs px-2 py-1 text-gray-300">← Prev</span>
                @else
                    <a href="{{ $myReports->previousPageUrl() }}"
                       class="text-xs px-2 py-1 text-blue-500 hover:text-blue-700">← Prev</a>
                @endif

                @foreach($myReports->getUrlRange(1, $myReports->lastPage()) as $page => $url)
                    @if($page == $myReports->currentPage())
                        <span class="text-xs px-2 py-1 bg-blue-500 text-white rounded">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                           class="text-xs px-2 py-1 text-gray-500 hover:bg-gray-100 rounded">{{ $page }}</a>
                    @endif
                @endforeach

                @if($myReports->hasMorePages())
                    <a href="{{ $myReports->nextPageUrl() }}"
                       class="text-xs px-2 py-1 text-blue-500 hover:text-blue-700">Next →</a>
                @else
                    <span class="text-xs px-2 py-1 text-gray-300">Next →</span>
                @endif
            </div>
        </div>
        @endif

    </div>

</div>

<script>
let index = 0;

function addRow() {
    const container = document.getElementById('rows-container');
    const div = document.createElement('div');
    div.className = 'bg-gray-50 border border-gray-200 rounded p-3';
    div.dataset.index = index;
    const i = index;

    div.innerHTML = `
        <div class="flex justify-between items-center mb-3 pb-2 border-b border-gray-200">
            <span class="text-xs font-semibold text-gray-600 card-index">Visit #${i + 1}</span>
            <button type="button" onclick="removeRow(this)"
                class="text-xs text-gray-400 border border-gray-200 px-2 py-0.5 rounded hover:text-red-500 hover:border-red-300 bg-white">
                ✕
            </button>
        </div>

        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Contact</p>
        <div class="space-y-2 mb-3">
            <input name="items[${i}][business_name]"
                class="border border-gray-300 rounded p-1.5 w-full text-xs bg-white focus:outline-none focus:border-blue-400"
                placeholder="Business name">
            <input name="items[${i}][location]"
                class="border border-gray-300 rounded p-1.5 w-full text-xs bg-white focus:outline-none focus:border-blue-400"
                placeholder="Location">
            <input name="items[${i}][contact_name]"
                class="border border-gray-300 rounded p-1.5 w-full text-xs bg-white focus:outline-none focus:border-blue-400"
                placeholder="Contact name">
            <input name="items[${i}][phone]"
                class="border border-gray-300 rounded p-1.5 w-full text-xs bg-white focus:outline-none focus:border-blue-400"
                placeholder="Phone number">
        </div>

        <hr class="border-gray-200 my-2">

        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Outcome</p>
        <div class="space-y-2 mb-3">
            <select name="items[${i}][contact_method]"
                class="border border-gray-300 rounded p-1.5 w-full text-xs bg-white focus:outline-none focus:border-blue-400">
                <option value="">Method</option>
                <option value="call">Call</option>
                <option value="visit">Visit</option>
            </select>
            <select name="items[${i}][status]"
                class="border border-gray-300 rounded p-1.5 w-full text-xs bg-white focus:outline-none focus:border-blue-400">
                <option value="">Status</option>
                <option value="interested">Interested</option>
                <option value="not_interested">Not Interested</option>
                <option value="follow_up">Follow Up</option>
            </select>
            <div class="flex gap-1" id="interest-group-${i}">
                <button type="button" onclick="setInterest(${i}, 'low', this)"
                    class="interest-btn flex-1 border border-gray-300 rounded py-1 text-xs text-gray-500 bg-white">↓ Low</button>
                <button type="button" onclick="setInterest(${i}, 'medium', this)"
                    class="interest-btn flex-1 border border-gray-300 rounded py-1 text-xs text-gray-500 bg-white">— Med</button>
                <button type="button" onclick="setInterest(${i}, 'high', this)"
                    class="interest-btn flex-1 border border-gray-300 rounded py-1 text-xs text-gray-500 bg-white">↑ High</button>
                <input type="hidden" name="items[${i}][interest_level]" id="interest-val-${i}">
            </div>
        </div>

        <hr class="border-gray-200 my-2">

        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Follow-up</p>
        <div class="space-y-2">
            <input name="items[${i}][next_action]"
                class="border border-gray-300 rounded p-1.5 w-full text-xs bg-white focus:outline-none focus:border-blue-400"
                placeholder="Next action">
            <input type="date" name="items[${i}][next_follow_up_date]"
                class="border border-gray-300 rounded p-1.5 w-full text-xs bg-white focus:outline-none focus:border-blue-400">
            <input name="items[${i}][remark]"
                class="border border-gray-300 rounded p-1.5 w-full text-xs bg-white focus:outline-none focus:border-blue-400"
                placeholder="Remark">
        </div>
    `;

    container.appendChild(div);
    index++;
    renumberCards();
}

function setInterest(i, level, btn) {
    const group = document.getElementById('interest-group-' + i);
    group.querySelectorAll('.interest-btn').forEach(b => {
        b.className = 'interest-btn flex-1 border border-gray-300 rounded py-1 text-xs text-gray-500 bg-white';
    });

    const styles = {
        low:    'interest-btn flex-1 border border-red-300 rounded py-1 text-xs text-red-600 bg-red-50',
        medium: 'interest-btn flex-1 border border-yellow-300 rounded py-1 text-xs text-yellow-600 bg-yellow-50',
        high:   'interest-btn flex-1 border border-green-300 rounded py-1 text-xs text-green-600 bg-green-50',
    };

    btn.className = styles[level];
    document.getElementById('interest-val-' + i).value = level;
}

function removeRow(btn) {
    btn.closest('[data-index]').remove();
    renumberCards();
}

function renumberCards() {
    document.querySelectorAll('#rows-container [data-index]').forEach((card, i) => {
        card.querySelector('.card-index').textContent = 'Visit #' + (i + 1);
    });
}

addRow();
</script>

@endsection