@extends('layouts.app-dashboard')

@section('title', 'Edit User')

@section('content')

<div class="max-w-2xl mx-auto p-6">

    <div class="mb-6">
        <a href="/admin/users" class="text-xs text-gray-400 hover:text-gray-600">← Back</a>
        <h2 class="text-xl font-semibold text-gray-700 mt-0.5">Edit User</h2>
    </div>

    <div class="bg-white border border-gray-100 rounded-lg p-6">

        <form action="/admin/users/{{ $user->id }}" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')

            @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 rounded-lg p-3 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div>
                <label class="block text-xs text-gray-500 mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                    class="border border-gray-200 rounded-lg p-2.5 w-full text-sm focus:outline-none focus:ring-1 focus:ring-blue-300">
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="border border-gray-200 rounded-lg p-2.5 w-full text-sm focus:outline-none focus:ring-1 focus:ring-blue-300">
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Role</label>
                <select name="role"
                    class="border border-gray-200 rounded-lg p-2.5 w-full text-sm focus:outline-none focus:ring-1 focus:ring-blue-300">
                    <option value="employee" {{ $user->role === 'employee' ? 'selected' : '' }}>Employee</option>
                    <option value="admin"    {{ $user->role === 'admin'    ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">New Password <span class="text-gray-300">(leave blank to keep current)</span></label>
                <input type="password" name="password"
                    class="border border-gray-200 rounded-lg p-2.5 w-full text-sm focus:outline-none focus:ring-1 focus:ring-blue-300"
                    placeholder="••••••••">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-sm py-2 rounded-lg font-medium">
                    Save Changes
                </button>
                <a href="/admin/users"
                    class="flex-1 text-center border border-gray-200 text-gray-500 hover:bg-gray-50 text-sm py-2 rounded-lg">
                    Cancel
                </a>
            </div>

        </form>

    </div>

</div>

@endsection