@extends('layouts.app-dashboard')

@section('title', 'Users')

@section('content')

<div class="max-w-6xl mx-auto p-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-700">Users</h2>
            <p class="text-sm text-gray-400 mt-1">Manage system users</p>
        </div>
        <a href="/admin/users/create"
           class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-4 py-2 rounded-lg font-medium">
            + Add User
        </a>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-600 rounded-lg p-3 mb-4 text-sm">
        {{ session('success') }}
    </div>
    @endif

    {{-- SUMMARY --}}
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-gray-50 rounded-lg px-4 py-3">
            <p class="text-xs text-gray-400 mb-1">Total Users</p>
            <p class="text-2xl font-semibold text-gray-700">{{ $users->count() }}</p>
        </div>
        <div class="bg-blue-50 rounded-lg px-4 py-3">
            <p class="text-xs text-blue-400 mb-1">Admins</p>
            <p class="text-2xl font-semibold text-blue-600">{{ $users->where('role', 'admin')->count() }}</p>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white border border-gray-100 rounded-lg overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide">#</th>
                    <th class="px-5 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide">Name</th>
                    <th class="px-5 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide">Phone</th>
                    <th class="px-5 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide">Role</th>
                    <th class="px-5 py-3 text-xs text-gray-400 font-medium uppercase tracking-wide text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($users as $index => $user)
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-5 py-3 text-xs text-gray-400">{{ $index + 1 }}</td>

                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            
                            <span class="text-sm font-medium text-gray-700">{{ $user->name }}</span>
                        </div>
                    </td>

                    <td class="px-5 py-3 text-sm text-gray-500">
                        {{ $user->phone }}
                    </td>

                    <td class="px-5 py-3">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium
                            {{ $user->role === 'admin' ? 'bg-blue-50 text-blue-600' : 'bg-gray-100 text-gray-500' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>

                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">

                            <a href="/admin/users/{{ $user->id }}/edit"
                               class="text-xs bg-blue-50 hover:bg-blue-100 text-blue-600 px-3 py-1.5 rounded-lg font-medium">
                                Edit
                            </a>

                            <form action="/admin/users/{{ $user->id }}" method="POST" class="inline"
                                  onsubmit="return confirm('Are you sure you want to delete {{ $user->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-xs bg-red-50 hover:bg-red-100 text-red-500 px-3 py-1.5 rounded-lg font-medium">
                                    Delete
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

@endsection