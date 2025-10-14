@extends('layouts.admin')

@section('title', 'Create User')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ __('admin.create_new_user') }}</h1>
        <p class="text-muted mb-0">{{ __('admin.add_new_user_to_system') }}</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn-minimal">
        <i class="bi bi-arrow-left"></i> {{ __('admin.back') }}
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card-minimal">
            <div class="card-body">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('admin.full_name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('admin.email_address') }} <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('admin.password') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                        <i class="bi bi-eye" id="password_icon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                        <i class="bi bi-eye" id="password_confirmation_icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">{{ __('admin.role') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" name="role">
                                    <option value="">Select {{ __('admin.role') }}</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="moderator" {{ old('role') == 'moderator' ? 'selected' : '' }}>Moderator</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">{{ __('admin.status') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status">
                                    <option value="">Select {{ __('admin.status') }}</option>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>{{ __('admin.active') }}</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn-minimal btn-primary">Create User</button>
                        <a href="{{ route('admin.users.index') }}" class="btn-minimal">{{ __('admin.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-minimal">
            <div class="card-header">
                <h5>{{ __('admin.user_guidelines') }}</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><i class="bi bi-check-circle text-success"></i> {{ __('admin.use_real_names') }}</li>
                    <li><i class="bi bi-check-circle text-success"></i> {{ __('admin.valid_email_addresses') }}</li>
                    <li><i class="bi bi-check-circle text-success"></i> {{ __('admin.strong_passwords_hint') }}</li>
                    <li><i class="bi bi-check-circle text-success"></i> {{ __('admin.appropriate_role_assignment') }}</li>
                </ul>
                
                <hr>
                
                <h6>{{ __('admin.role_permissions') }}:</h6>
                <ul class="list-unstyled small">
                    <li><span class="badge bg-danger">{{ __('admin.roles.admin') }}</span> - {{ __('admin.full_system_access') }}</li>
                    <li><span class="badge bg-warning">{{ __('admin.roles.moderator') }}</span> - {{ __('admin.content_management_only') }}</li>
                </ul>
                
                <hr>
                
                <h6>{{ __('admin.status_options') }}:</h6>
                <ul class="list-unstyled small">
                    <li><span class="badge bg-success">{{ __('admin.active') }}</span> - {{ __('admin.can_login_access_system') }}</li>
                    <li><span class="badge bg-secondary">{{ __('admin.inactive') }}</span> - {{ __('admin.account_disabled') }}</li>
                </ul>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5>Password Requirements</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled small">
                    <li><i class="bi bi-check-circle text-success"></i> Minimum 8 characters</li>
                    <li><i class="bi bi-check-circle text-success"></i> Mix of letters and numbers</li>
                    <li><i class="bi bi-check-circle text-success"></i> Special characters recommended</li>
                    <li><i class="bi bi-check-circle text-success"></i> Avoid common passwords</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Password toggle function
    function togglePassword(fieldId) {
        const passwordField = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '_icon');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            passwordField.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        
        function validatePassword() {
            if (confirmPasswordInput.value && passwordInput.value !== confirmPasswordInput.value) {
                confirmPasswordInput.setCustomValidity('Passwords do not match');
            } else {
                confirmPasswordInput.setCustomValidity('');
            }
        }
        
        passwordInput.addEventListener('input', validatePassword);
        confirmPasswordInput.addEventListener('input', validatePassword);
    });
</script>
@endpush

@push('styles')
<style>
    .input-group .btn {
        border-left: 0;
    }
    
    .input-group .form-control:focus {
        border-right: 0;
    }
    
    .input-group .btn:hover {
        background-color: #e9ecef;
        border-color: #ced4da;
    }
</style>
@endpush

@endsection
