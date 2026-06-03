<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reporting System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800 h-screen overflow-hidden">

<div class="flex h-screen">

    {{-- SIDEBAR --}}
    <aside class="w-52 bg-white border-r px-4 py-5 sticky top-0 h-screen overflow-y-auto flex-shrink-0">

        <h2 class="text-sm font-semibold text-gray-700 mb-6 px-2">
            📊 Reporting System
        </h2>

        <nav class="space-y-0.5 text-sm">

            {{-- USER ONLY --}}
            @if(!auth()->user()->isAdmin())

            <a href="/dashboard"
               class="block px-3 py-2 rounded-lg text-gray-600 hover:bg-gray-100">
                Dashboard
            </a>

            <div x-data="{ open: false }">

                <button @click="open=!open"
                    class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-gray-600 hover:bg-gray-100">

                    <span>Report Management</span>

                    <svg :class="open ? 'rotate-180':''"
                        class="w-3.5 h-3.5 text-gray-400 transition-transform"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M19 9l-7 7-7-7"/>
                    </svg>

                </button>

                <div x-show="open"
                    x-transition
                    class="ml-3 mt-1 space-y-1 border-l border-gray-100 pl-3">

                    <a href="/reports/create"
                        class="block px-2 py-1.5 text-xs rounded-lg hover:bg-gray-100">
                        Create Report
                    </a>

                    <a href="/reports/my"
                        class="block px-2 py-1.5 text-xs rounded-lg hover:bg-gray-100">
                        My Reports
                    </a>

                    <a href="/reports"
                        class="block px-2 py-1.5 text-xs rounded-lg hover:bg-gray-100">
                        All Reports
                    </a>

                </div>

            </div>

            @php
                $unreadCount=
                \App\Models\Announcement::whereDoesntHave(
                    'reads',
                    function($q){
                        $q->where(
                            'user_id',
                            auth()->id()
                        );
                    }
                )->count();
            @endphp

            <a href="/announcements/announcementread"
               class="flex items-center justify-between px-3 py-2 rounded-lg text-gray-600 hover:bg-gray-100">

                <span>Announcements</span>

                @if($unreadCount>0)
                <span class="bg-blue-500 text-white text-xs px-2 rounded-full">
                    {{ $unreadCount }}
                </span>
                @endif

            </a>
             <a href="/feedbacks"
   class="block px-3 py-2 rounded-lg text-gray-600 hover:bg-gray-100">
    Feedback
</a>
        

            @endif


            {{-- ADMIN --}}
            @if(auth()->user()->isAdmin())

            <p class="text-xs text-gray-400 uppercase px-3 py-2">
                Admin
            </p>

            <a href="/admin/dashboard"
               class="block px-3 py-2 rounded-lg hover:bg-gray-100">
                Dashboard
            </a>

            <a href="/reports"
               class="block px-3 py-2 rounded-lg hover:bg-gray-100">
                All Reports
            </a>

            <a href="/admin/users"
               class="block px-3 py-2 rounded-lg hover:bg-gray-100">
                Users
            </a>

            <a href="/admin/announcements"
               class="block px-3 py-2 rounded-lg hover:bg-gray-100">
                Announcements
            </a>

            @endif
           

        </nav>

    </aside>



    {{-- MAIN AREA --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden">

        {{-- TOPBAR --}}
        <header class="bg-white border-b px-6 py-3 flex justify-between items-center">

            <h1 class="text-sm font-semibold text-gray-700">
                @yield('title','Dashboard')
            </h1>


            <div class="flex items-center gap-4">

                {{-- NOTIFICATION ICON --}}

                @php

                    $notificationCount=0;

                    if(!auth()->user()->isAdmin()){

                        $announcementCount=
                        \App\Models\Announcement
                        ::whereDoesntHave(
                            'reads',
                            function($q){
                                $q->where(
                                'user_id',
                                auth()->id()
                                );
                            }
                        )->count();


                    //     $feedbackCount=
                    //     \App\Models\Report
                    //     ::where(
                    //         'user_id',
                    //         auth()->id()
                    //     )
                    //     ->whereNotNull(
                    //         'feedback'
                    //     )
                    //     ->where(
                    //         'seen',
                    //         false
                    //     )
                    //     ->count();

                    //     $notificationCount=
                    //     $announcementCount+
                    //     $feedbackCount;
                    // 
                    }

                @endphp


                <a href="/announcements/announcementread"
                   class="relative p-2 rounded-full hover:bg-gray-100">

                    <svg class="w-6 h-6 text-gray-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032
                        2.032 0 0118
                        14.158V11a6.002
                        6.002 0
                        00-4-5.659V5a2
                        2 0
                        10-4 0v.341C7.67
                        6.165 6
                        8.388 6
                        11v3.159c0
                        .538-.214
                        1.055-.595
                        1.436L4
                        17h5m6
                        0v1a3 3
                        0 11-6
                        0v-1m6
                        0H9"/>

                    </svg>


                    @if($notificationCount>0)

                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full">

                        {{ $notificationCount }}

                    </span>

                    @endif

                </a>


                {{-- PROFILE --}}

                <div class="relative"
                    x-data="{open:false}">

                    <button
                    @click="open=!open"
                    class="flex items-center gap-2 hover:bg-gray-50 px-3 py-1.5 rounded-lg">

                        <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 text-xs font-bold flex items-center justify-center">

                            {{ strtoupper(substr(auth()->user()->name,0,2)) }}

                        </div>

                        <span class="text-sm text-gray-600">

                            {{ auth()->user()->name }}

                        </span>

                        <svg class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7"/>

                        </svg>

                    </button>



                    <div x-show="open"
                         @click.outside="open=false"
                         x-transition
                         class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow z-50">

                        <div class="px-4 py-3 border-b">

                            <p class="text-sm font-semibold">
                                {{ auth()->user()->name }}
                            </p>

                            <p class="text-xs text-gray-400">
                                {{ auth()->user()->role }}
                            </p>

                        </div>


                        <a href="{{ route('profile.edit') }}"
                           class="block px-4 py-2 hover:bg-gray-50">

                            Edit Profile

                        </a>


                        <form action="/logout"
                              method="POST">

                            @csrf

                            <button
                                class="w-full text-left px-4 py-2 text-red-500 hover:bg-red-50">

                                Logout

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </header>



        {{-- CONTENT --}}
        <main class="flex-1 overflow-y-auto p-6">

            @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
            @endif


            @yield('content')

        </main>

    </div>

</div>

</body>
</html>