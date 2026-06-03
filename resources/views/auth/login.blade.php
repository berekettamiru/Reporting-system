<x-guest-layout>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
        <div class="w-full max-w-sm">

            {{-- HEADER --}}
            <div class="text-center mb-8">
                <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h1 class="text-xl font-semibold text-gray-700">ReportSys</h1>
                <p class="text-sm text-gray-400 mt-1">Sign in to your account</p>
            </div>

            {{-- FORM --}}
            <div class="bg-white border border-gray-100 rounded-xl p-6">

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    {{-- PHONE --}}
                    <div>
                        <label for="phone" class="block text-xs text-gray-500 mb-1">Phone number</label>
                        <input id="phone"
                               type="tel"
                               name="phone"
                               value="{{ old('phone') }}"
                               required
                               autofocus
                               placeholder="09XXXXXXXX"
                               class="border border-gray-200 rounded-lg p-2.5 w-full text-sm focus:outline-none focus:ring-1 focus:ring-blue-300">
                        @error('phone')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- PASSWORD --}}
                    <div>
                        <label for="password" class="block text-xs text-gray-500 mb-1">Password</label>
                        <input id="password"
                               type="password"
                               name="password"
                               required
                               autocomplete="current-password"
                               placeholder="••••••••"
                               class="border border-gray-200 rounded-lg p-2.5 w-full text-sm focus:outline-none focus:ring-1 focus:ring-blue-300">
                        @error('password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- REMEMBER ME --}}
                    <div class="flex items-center gap-2">
                        <input id="remember_me"
                               type="checkbox"
                               name="remember"
                               class="rounded border-gray-300 text-blue-500 focus:ring-blue-300">
                        <label for="remember_me" class="text-xs text-gray-500">Remember me</label>
                    </div>

                    {{-- SUBMIT --}}
                    <button type="submit"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium py-2.5 rounded-lg transition">
                        Sign in
                    </button>

                </form>

            </div>

            <p class="text-center text-xs text-gray-400 mt-6">
                Reporting System © {{ date('Y') }}
            </p>

        </div>
    </div>

</x-guest-layout>