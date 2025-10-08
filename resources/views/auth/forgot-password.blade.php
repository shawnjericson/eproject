@extends('layouts.auth')

@section('title', 'Quên Mật Khẩu')

@section('content')
<div class="relative z-40 w-full max-w-6xl bg-white rounded-3xl shadow-2xl overflow-hidden flex min-h-[600px]">
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
            <h1 class="font-serif text-4xl font-bold mb-4 drop-shadow-lg">{{ __('admin.global_heritage') }}</h1>
            <p class="text-lg opacity-95 leading-relaxed max-w-md mx-auto mb-10">{{ __('admin.tagline') }}</p>
        </div>
    </div>

    <!-- Right Side - Forgot Password Form -->
    <div class="flex-1 p-16 flex flex-col justify-center">
        <div class="mb-10">
            <h2 class="font-serif text-3xl font-semibold text-gray-900 mb-3">{{__('admin.forgot_password')}}</h2>
            <p class="text-gray-600">{{__('admin.forgotpassword_tagline')}}</p>
        </div>

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

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                    </div>
                    <input type="text"
                           class="block w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('email') border-red-300 @enderror"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="{{__('admin.forgotpassword_placeholder')}}"
                           >
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-gradient-to-r from-primary-600 to-primary-700 text-white py-3 px-6 rounded-xl font-semibold hover:from-primary-700 hover:to-primary-800 focus:ring-4 focus:ring-primary-200 transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
                {{__('admin.forgotpassword_proceed')}}
            </button>
        </form>

        <!-- Back to Login -->
        <div class="text-center mt-2">
            <a href="{{ route('login') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                ← {{__('admin.forgotpassword_back')}}
            </a>
        </div>

        <!-- Help Text -->
        <div class="text-center mt-6">
            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-xl">
                <p class="text-sm">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{__('admin.forgotpassword_helptext')}}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
