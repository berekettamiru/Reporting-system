@extends('layouts.app-dashboard')

@section('title', 'Create Announcement')

@section('content')

<div class="max-w-2xl mx-auto p-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-start mb-6">

        <div>
            {{-- <a href="/admin/announcements"
               class="text-xs text-gray-400 hover:text-gray-600">
                ← Back
            </a> --}}

            <h2 class="text-xl font-semibold text-gray-700 mt-1">
                Create Announcement
            </h2>

            <p class="text-sm text-gray-400 mt-1">
                Post a new announcement to all users
            </p>
        </div>

        {{-- ANNOUNCEMENT LIST BUTTON --}}
        <a href="/admin/announcements"
           class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-4 py-2 rounded-lg font-medium">
            + Announcement List
        </a>

    </div>

    <form method="POST" action="/admin/announcements">
        @csrf

        {{-- ERRORS --}}
        @if ($errors->any())

        <div class="bg-red-50 border border-red-200 text-red-600 rounded-lg p-4 mb-4 text-sm">

            <p class="font-semibold mb-1">
                Please fix the following:
            </p>

            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

        @endif

        {{-- FORM CARD --}}
        <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-4">

            {{-- TITLE --}}
            <div>

                <label class="block text-xs text-gray-500 mb-1">
                    Title
                </label>

                <input
                    type="text"
                    name="title"
                    value="{{ old('title') }}"
                    placeholder="Announcement title..."
                    class="border border-gray-200 rounded-lg p-2.5 w-full text-sm focus:outline-none focus:ring-1 focus:ring-blue-300">

            </div>

            {{-- CATEGORY --}}
            <div>

                <label class="block text-xs text-gray-500 mb-1">
                    Category
                </label>

                <select
                    name="category"
                    class="border border-gray-200 rounded-lg p-2.5 w-full text-sm focus:outline-none focus:ring-1 focus:ring-blue-300">

                    <option value="general"
                        {{ old('category') === 'general' ? 'selected' : '' }}>
                        General
                    </option>

                    <option value="reminder"
                        {{ old('category') === 'reminder' ? 'selected' : '' }}>
                        Reminder
                    </option>

                    <option value="update"
                        {{ old('category') === 'update' ? 'selected' : '' }}>
                        Update
                    </option>

                    <option value="important"
                        {{ old('category') === 'important' ? 'selected' : '' }}>
                        Important
                    </option>

                </select>

            </div>

            {{-- MESSAGE --}}
            <div>

                <label class="block text-xs text-gray-500 mb-1">
                    Message
                </label>

                <textarea
                    name="body"
                    rows="5"
                    placeholder="Write your announcement here..."
                    class="border border-gray-200 rounded-lg p-2.5 w-full text-sm focus:outline-none focus:ring-1 focus:ring-blue-300 resize-none">{{ old('body') }}</textarea>

            </div>

            {{-- PREVIEW --}}
            <div id="preview"
                 class="hidden border border-dashed border-gray-200 rounded-lg p-4">

                <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">
                    Preview
                </p>

                <div class="flex items-center gap-2 mb-2">

                    <span id="preview-badge"
                          class="text-xs px-2.5 py-1 rounded-full font-medium bg-blue-50 text-blue-600">
                    </span>

                    <span class="text-xs text-gray-400">
                        {{ now()->format('M j, Y') }}
                    </span>

                </div>

                <h3 id="preview-title"
                    class="text-sm font-semibold text-gray-700 mb-1">
                </h3>

                <p id="preview-body"
                   class="text-sm text-gray-500 leading-relaxed">
                </p>

            </div>

        </div>

        {{-- BUTTONS --}}
        <div class="flex gap-3 mt-4">

            <button type="button"
                    onclick="togglePreview()"
                    class="flex-1 border border-gray-200 text-gray-500 text-sm py-2 rounded-lg hover:bg-gray-50">

                Preview

            </button>

            <button type="submit"
                    class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-sm py-2 rounded-lg font-medium">

                Post Announcement

            </button>

        </div>

    </form>

</div>

<script>

const categoryStyles = {

    general: {
        label: 'General',
        cls: 'bg-blue-50 text-blue-600'
    },

    reminder: {
        label: 'Reminder',
        cls: 'bg-yellow-50 text-yellow-600'
    },

    update: {
        label: 'Update',
        cls: 'bg-green-50 text-green-600'
    },

    important: {
        label: 'Important',
        cls: 'bg-red-50 text-red-500'
    },

};

function togglePreview() {

    const preview  = document.getElementById('preview');
    const title    = document.querySelector('[name="title"]').value;
    const body     = document.querySelector('[name="body"]').value;
    const category = document.querySelector('[name="category"]').value;

    if (preview.classList.contains('hidden')) {

        const style = categoryStyles[category];

        document.getElementById('preview-title').textContent =
            title || 'No title';

        document.getElementById('preview-body').textContent =
            body || 'No message';

        document.getElementById('preview-badge').textContent =
            style.label;

        document.getElementById('preview-badge').className =
            'text-xs px-2.5 py-1 rounded-full font-medium ' + style.cls;

        preview.classList.remove('hidden');

    } else {

        preview.classList.add('hidden');

    }
}

</script>

@endsection