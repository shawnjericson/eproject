@extends('layouts.auth')

@section('title', 'Đặt Lại Mật Khẩu')

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
            <h1 class="font-serif text-4xl font-bold mb-4 drop-shadow-lg">Global Heritage</h1>
            <p class="text-lg opacity-95 leading-relaxed max-w-md mx-auto mb-10">{{ __('admin.tagline') }}</p>
        </div>
    </div>

    <!-- Right Side - Reset Password Form -->
    <div class="flex-1 p-16 flex flex-col justify-center">
        <div class="mb-2">
            <h2 class="font-serif text-3xl font-semibold text-gray-900 mb-3">{{ __('admin.resetpassword_title') }}</h2>
            <p class="text-gray-600">{{ __('admin.resetpassword_tagline') }}</p>
        </div>

        @if($errors->any())
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
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
            @csrf

            <!-- New Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.resetpassword_newpassword') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <input type="password"
                           class="block w-full pl-12 pr-12 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('password') border-red-300 @enderror"
                           id="password"
                           name="password"
                           placeholder="{{ __('admin.resetpassword_placeholder') }}"
                           required>
                    <button type="button" id="toggleResetPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-gray-700" aria-label="Toggle password visibility">
                        <svg id="eyeIconReset" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.resetpassword_confirmation') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <input type="password"
                           class="block w-full pl-12 pr-12 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                           id="password_confirmation"
                           name="password_confirmation"
                           placeholder="{{ __('admin.resetpassword_confirmationplaceholer') }}"
                           required>
                    <button type="button" id="toggleResetPasswordConfirm" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-gray-700" aria-label="Toggle password visibility">
                        <svg id="eyeIconResetConfirm" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Password Requirements -->
            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-xl">
                <h4 class="text-sm font-medium text-blue-800 mb-2">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ __('admin.resetpassword_required') }}
                </h4>
                <ul class="text-sm text-blue-700 space-y-1 ml-5">
                    <li>{{ __('admin.resetpassword_required1') }}</li>
                    <li>{{ __('admin.resetpassword_required2') }}</li>
                    <li>{{ __('admin.resetpassword_required3') }}</li>
                </ul>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-primary-700 text-white py-3 px-6 rounded-xl font-semibold hover:from-green-700 hover:to-primary-800 focus:ring-4 focus:ring-green-200 transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ __('admin.resetpassword_title') }}
            </button>
        </form>

        <!-- Success Message -->
        <div class="text-center mt-6">
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
                <p class="text-sm">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ __('admin.resetpassword_message') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  function wireToggle(inputId, buttonId, iconId) {
    const input = document.getElementById(inputId);
    const btn = document.getElementById(buttonId);
    const icon = document.getElementById(iconId);
    if (!input || !btn || !icon) return;
    btn.addEventListener('click', function() {
      const hidden = input.type === 'password';
      input.type = hidden ? 'text' : 'password';
      icon.innerHTML = hidden
        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.05 10.05 0 012.51-4.263M6.18 6.18A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.05 10.05 0 01-4.133 5.062M15 12a3 3 0 10-6 0 3 3 0 006 0z" />'
        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
    });
  }
  wireToggle('password', 'toggleResetPassword', 'eyeIconReset');
  wireToggle('password_confirmation', 'toggleResetPasswordConfirm', 'eyeIconResetConfirm');
});
</script>
@endpush
