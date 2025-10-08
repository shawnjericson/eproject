@extends('layouts.auth')

@section('title', __('admin.register'))

@section('content')
<div class="relative z-10 w-full max-w-6xl bg-white rounded-3xl shadow-2xl overflow-hidden flex min-h-[700px] max-h-[110vh]">
    <!-- Left Side - Branding -->
    <div class="flex-1 bg-gradient-to-br from-primary-600 to-primary-800 p-8 lg:p-12 flex flex-col justify-center items-center text-center text-white relative overflow-hidden">
        <!-- Animated Background -->
        <div class="absolute -top-1/2 -right-1/2 w-[200%] h-[200%] bg-gradient-radial from-white/10 to-transparent animate-spin" style="animation-duration: 30s;"></div>

        <div class="relative z-10">
            <div class="w-24 h-24 lg:w-32 lg:h-32 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                <img src="{{ asset('favicon_io/android-chrome-192x192.png') }}"
                     alt="Global Heritage Logo"
                     class="w-16 h-16 lg:w-20 lg:h-20 object-contain rounded-xl">
            </div>
            <h1 class="font-serif text-2xl lg:text-3xl font-bold mb-3 drop-shadow-lg">{{ __('admin.join_community') }}</h1>
            <p class="text-sm lg:text-base opacity-95 leading-relaxed max-w-sm mx-auto mb-6">{{ __('admin.register_tagline') }}</p>
        </div>
    </div>

    <!-- Right Side - Register Form -->
    <div class="flex-1 p-6 lg:p-12 flex flex-col justify-center overflow-y-auto max-h-[110vh]">

        <!-- @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <p class="text-sm">
                            {{ __('There were :count errors. Please review the highlighted fields.', ['count' => $errors->count()]) }}
                        </p>
                    </div>
                    <button type="button" id="toggleErrorDetails" class="text-xs text-red-700 hover:text-red-800 underline">
                        {{ __('Show details') }}
                    </button>
                </div>
                <div id="errorDetails" class="mt-2 hidden max-h-32 overflow-auto text-sm leading-6">
                    @foreach($errors->all() as $error)
                        <div>• {{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif -->

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 flex items-start gap-2">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <div class="font-medium">{{ __('Account created') }}</div>
                    <div class="text-sm">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        <div>
            <h2 class="font-serif text-2xl font-semibold text-gray-900 mb-2">{{ __('admin.create_account') }}</h2>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.full_name') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <input type="text"
                           class="block w-full pl-12 pr-4 py-2.5 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('name') border-red-300 @enderror"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="{{ __('admin.enter_full_name') }}"
                           
                           autofocus>
                </div>
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.email') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                    </div>
                    <input type="text"
                           class="block w-full pl-12 pr-4 py-2.5 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('email') border-red-300 @enderror"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="{{ __('admin.enter_email') }}"
                           >
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.password') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <input type="password"
                           class="block w-full pl-12 pr-12 py-2.5 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('password') border-red-300 @enderror"
                           id="password"
                           name="password"
                           placeholder="{{ __('admin.enter_password') }}"
                           >
                    <button type="button" id="toggleRegPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700" aria-label="Toggle password visibility">
                        <svg id="eyeIconReg" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.confirm_password') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <input type="password"
                           class="block w-full pl-12 pr-12 py-2.5 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                           id="password_confirmation"
                           name="password_confirmation"
                           placeholder="{{ __('admin.confirm_password') }}"
                           >
                    <button type="button" id="toggleRegPasswordConfirm" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700" aria-label="Toggle password visibility">
                        <svg id="eyeIconRegConfirm" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Terms Agreement -->
            <div class="flex items-center">
                <input type="checkbox"
                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                       id="terms"
                       name="terms"
                       >
                <label class="ml-2 block text-sm text-gray-700" for="terms">
                    {{ __('admin.agree_terms') }}
                    <a href="#" class="text-primary-600 hover:text-primary-700">{{ __('admin.terms_conditions') }}</a>
                </label>
            </div>
            @error('terms')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-gradient-to-r from-primary-600 to-primary-700 text-white py-2.5 px-6 rounded-lg font-semibold hover:from-primary-700 hover:to-primary-800 focus:ring-4 focus:ring-primary-200 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center gap-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                {{ __('admin.create_account') }}
            </button>
        </form>

        <!-- Login Link -->
        <div class="text-center mt-1">
            <p class="text-gray-600 mb-3 text-sm">{{ __('admin.already_have_account') }}</p>
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2 border-2 border-primary-600 text-primary-600 rounded-lg font-medium hover:bg-primary-50 transition-colors text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
                {{ __('admin.login') }}
            </a>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const toggleBtn = document.getElementById('toggleErrorDetails');
  const details = document.getElementById('errorDetails');
  if (toggleBtn && details) {
    toggleBtn.addEventListener('click', function() {
      const hidden = details.classList.contains('hidden');
      details.classList.toggle('hidden');
      toggleBtn.textContent = hidden ? 'Hide details' : 'Show details';
    });
  }

  // Scroll to first invalid field (if any)
  const firstInvalid = document.querySelector('.border-red-300');
  if (firstInvalid && firstInvalid.scrollIntoView) {
    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
});
</script>
@endpush
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
  wireToggle('password', 'toggleRegPassword', 'eyeIconReg');
  wireToggle('password_confirmation', 'toggleRegPasswordConfirm', 'eyeIconRegConfirm');
});
</script>
@endpush
