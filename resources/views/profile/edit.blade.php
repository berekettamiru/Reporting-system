@extends('layouts.app-dashboard')

@section('title', 'Edit Profile')

@section('content')

<div class="max-w-lg mx-auto p-6">

    <div class="mb-6">
        

        <h2 class="text-xl font-semibold text-gray-700 mt-1">
            Edit Profile
        </h2>
    </div>

    <div class="bg-white border border-gray-100 rounded-lg p-6">

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-600 p-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 p-3 rounded-lg text-xs">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label class="block text-xs text-gray-500 mb-1">
                    Name
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name',$user->name) }}"
                    class="border border-gray-200 rounded-lg p-2.5 w-full text-sm"
                >
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">
                    Phone
                </label>

                <input
                    type="text"
                    name="phone"
                    value="{{ old('phone',$user->phone) }}"
                    class="border border-gray-200 rounded-lg p-2.5 w-full text-sm"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg"
            >
                Save Changes
            </button>

        </form>

    </div>

</div>

@endsection