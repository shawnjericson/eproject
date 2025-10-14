@extends('layouts.auth')

@section('title', __('admin.login'))

@section('content')
<div class="relative z-10 w-full max-w-6xl bg-white rounded-3xl shadow-2xl overflow-hidden flex min-h-[600px]">
    <!-- Left Side - Branding -->
    <div class="flex-1 bg-gradient-to-br from-primary-600 to-primary-800 p-16 flex flex-col justify-center items-center text-center text-white relative overflow-hidden">
        <!-- Animated Background -->
        <div class="absolute -top-1/2 -right-1/2 w-[200%] h-[200%] bg-gradient-radial from-white/10 to-transparent animate-spin" style="animation-duration: 30s;"></div>

        <div class="relative z-10">
            <div class="w-40 h-40 bg-white rounded-full flex items-center justify-center mx-auto mb-8 shadow-lg">
                <img src="{{ asset('favicon_io/android-chrome-192x192.png') }}"
                    alt="Global Heritage Logo"
                    class="w-40 h-40 object-contain rounded-xl">
            </div>
            <h1 class="font-serif text-4xl font-bold mb-4 drop-shadow-lg">Global Heritage</h1>
            <p class="text-lg opacity-95 leading-relaxed max-w-md mx-auto mb-10">{{ __('admin.tagline') }}</p>
        </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="flex-1 p-16 flex flex-col justify-center">
        <div class="mb-10">
            <h2 class="font-serif text-3xl font-semibold text-gray-900 mb-3">{{ __('admin.login_to_account') }}</h2>
            <p class="text-gray-600">{{ __('admin.welcome_back_login') }}</p>
        </div>

        @error('system')
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <div>
                {{ $message }}
            </div>
        </div>
        @enderror

        <!-- @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <div>
                @foreach($errors->all() as $error)
                {{ $error }}
                @endforeach
            </div>
        </div>
        @endif -->

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.email') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                    </div>
                    <input type="text"
                        class="block w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('email') border-red-300 @enderror"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="{{ __('admin.enter_email') }}"
                        autofocus>
                </div>
                @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.password') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <input type="password"
                        class="block w-full pl-12 pr-12 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('password') border-red-300 @enderror"
                        id="password"
                        name="password"
                        placeholder="{{ __('admin.enter_password') }}">
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-gray-700" aria-label="Toggle password visibility">
                        <svg id="eyeIcon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <!-- <div class="flex items-center">
                <input type="checkbox"
                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                    id="remember"
                    name="remember">
                <label class="ml-2 block text-sm text-gray-700" for="remember">
                    {{ __('admin.remember_me') }}
                </label>
            </div> -->

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-gradient-to-r from-primary-600 to-primary-700 text-white py-3 px-6 rounded-xl font-semibold hover:from-primary-700 hover:to-primary-800 focus:ring-4 focus:ring-primary-200 transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
                {{ __('admin.login') }}
            </button>
        </form>

        @push('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.getElementById('togglePassword');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordInput && toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    const isHidden = passwordInput.type === 'password';
                    passwordInput.type = isHidden ? 'text' : 'password';
                    // Toggle icon style (simple swap via path data or CSS class)
                    if (isHidden) {
                        eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.05 10.05 0 012.51-4.263M6.18 6.18A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.05 10.05 0 01-4.133 5.062M15 12a3 3 0 10-6 0 3 3 0 006 0z" />';
                    } else {
                        eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
                    }
                });
            }
        });
        </script>
        @endpush

        <!-- Forgot Password Link -->
        <div class="text-center mt-2">
            <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                {{__('admin.forgot_password')}}
            </a>
        </div>

        <!-- Register Link -->
        <div class="text-center mt-2">
            <p class="text-gray-600 mb-4">{{ __('admin.dont_have_account') }}</p>
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3 border-2 border-primary-600 text-primary-600 rounded-xl font-medium hover:bg-primary-50 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                {{ __('admin.create_account') }}
            </a>
        </div>
    </div>
</div>
@endsection