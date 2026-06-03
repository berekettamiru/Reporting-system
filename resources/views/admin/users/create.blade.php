@extends('layouts.app-dashboard')

@section('title', 'Create User')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="bg-white p-8 rounded-2xl shadow-sm border">

        <h2 class="text-xl font-semibold mb-6">Add New User</h2>

        <form method="POST" action="/admin/users" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <label class="text-sm text-gray-600">Full Name</label>
                <input type="text" name="name"
                       class="w-full mt-1 border rounded-lg p-3 focus:ring focus:ring-gray-200">
            </div>

            <!-- Phone -->
            <div>
                <label class="text-sm text-gray-600">Phone</label>
                <input type="text" name="phone"
                       class="w-full mt-1 border rounded-lg p-3 focus:ring focus:ring-gray-200">
            </div>

            <!-- Role -->
           <select name="role" class="w-full mt-1 border rounded-lg p-3">
    <option value="">-- Select Role --</option>
    <option value="employee">Employee</option>
    <option value="admin">Admin</option>
</select>

            <!-- Password -->
            <div>
                <label class="text-sm text-gray-600">Password</label>
                <input type="password" name="password"
                       class="w-full mt-1 border rounded-lg p-3">
            </div>

            <!-- Button -->
            <div class="pt-4">
                <button class="bg-gray-900 text-white px-6 py-3 rounded-xl hover:opacity-90">
                    Create User
                </button>
            </div>

        </form>

    </div>

</div>

@endsection