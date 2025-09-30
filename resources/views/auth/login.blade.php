@extends('layouts.auth')

@section('title', __('admin.login'))

@section('content')
<div class="auth-container">
    <!-- Left Side - Branding -->
    <div class="auth-brand">
        <div class="brand-content">
            <div class="brand-logo">
                <i class="bi bi-globe-americas"></i>
            </div>
            <h1 class="brand-title">{{ __('admin.global_heritage') }}</h1>
            <p class="brand-tagline">{{ __('admin.tagline') }}</p>

            <div class="brand-features">
                
            </div>
        </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="auth-form-container">
        <div class="auth-header">
            <h2 class="auth-title">{{ __('admin.login_to_account') }}</h2>
            <p class="auth-subtitle">{{ __('admin.welcome_back_login') }}</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                @foreach($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="form-group">
                <label for="email" class="form-label">{{ __('admin.email') }}</label>
                <div class="input-group">
                    <i class="bi bi-envelope"></i>
                    <input type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="{{ __('admin.enter_email') }}"
                           required
                           autofocus>
                </div>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password" class="form-label">{{ __('admin.password') }}</label>
                <div class="input-group">
                    <i class="bi bi-lock"></i>
                    <input type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           id="password"
                           name="password"
                           placeholder="{{ __('admin.enter_password') }}"
                           required>
                </div>
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="form-check">
                <input type="checkbox"
                       class="form-check-input"
                       id="remember"
                       name="remember">
                <label class="form-check-label" for="remember">
                    {{ __('admin.remember_me') }}
                </label>
            </div>

            <!-- Submit Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    {{ __('admin.login') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
