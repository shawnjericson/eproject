@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ __('admin.edit') }} {{ __('admin.user') }}</h1>
        <p class="text-muted mb-0">{{ __('admin.editing_user_info') }}: <strong>{{ $user->name }}</strong></p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.users.show', $user) }}" class="btn-minimal btn-outline-info">
                <i class="bi bi-eye me-1"></i>{{ __('admin.view') }}
        </a>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn-minimal btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>{{ __('admin.back') }}
        </a>
    </div>
</div>

<div class="row">
    <!-- Main Form -->
    <div class="col-lg-8">
        <div class="card-minimal">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person-gear me-2"></i>{{ __('admin.user_information') }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Name Field -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-semibold">
                                {{ __('admin.user_name') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                class="form-control @error('name') is-invalid @enderror"
                                id="name" 
                                name="name" 
                                value="{{ old('name', $user->name) }}"
                                placeholder="{{ __('admin.enter_full_name') }}">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                        <!-- Email Field -->
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-semibold">
                                {{ __('admin.user_email') }} <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                class="form-control @error('email') is-invalid @enderror"
                                id="email" 
                                name="email" 
                                value="{{ old('email', $user->email) }}"
                                placeholder="{{ __('admin.enter_email_address') }}">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Password Field -->
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-semibold">
                                {{ __('admin.new_password') }}
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                    class="form-control @error('password') is-invalid @enderror"
                                    id="password" 
                                    name="password"
                                    placeholder="{{ __('admin.password_leave_empty_hint') }}">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                    <i class="bi bi-eye" id="password_icon"></i>
                                </button>
                            </div>
                            <div class="form-text">{{ __('admin.password_leave_empty_hint') }}</div>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label fw-semibold">
                                {{ __('admin.confirm_new_password') }}
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    id="password_confirmation" 
                                    name="password_confirmation"
                                    placeholder="{{ __('admin.password_confirmation_hint') }}">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                    <i class="bi bi-eye" id="password_confirmation_icon"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Role Field -->
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label fw-semibold">
                                {{ __('admin.user_role') }} <span class="text-danger">*</span>
                            </label>
                                <select class="form-select @error('role') is-invalid @enderror"
                                id="role" 
                                name="role"
                                @if(auth()->user()->role !== 'admin' || $user->id === auth()->id()) disabled @endif>
                                <option value="">{{ __('admin.select_role') }}</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                    {{ __('admin.roles.admin') }}
                                </option>
                                <option value="moderator" {{ old('role', $user->role) == 'moderator' ? 'selected' : '' }}>
                                    {{ __('admin.roles.moderator') }}
                                </option>
                                </select>
                            
                            @if(auth()->user()->role !== 'admin')
                                <div class="form-text text-warning">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    {{ __('admin.roles.moderator_cannot_change_role') }}
                                </div>
                            @elseif($user->id === auth()->id())
                                <div class="form-text text-warning">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    {{ __('admin.roles.cannot_change_own_role') }}
                                </div>
                            @endif

                            @if(auth()->user()->role !== 'admin' || $user->id === auth()->id())
                                <input type="hidden" name="role" value="{{ $user->role }}">
                                @endif

                                @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        <!-- Status Field -->
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label fw-semibold">
                                {{ __('admin.user_status') }} <span class="text-danger">*</span>
                            </label>
                                <select class="form-select @error('status') is-invalid @enderror"
                                id="status" 
                                name="status"
                                @if($user->id === auth()->id()) disabled @endif>
                                <option value="">{{ __('admin.select_status') }}</option>
                                <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>
                                    {{ __('admin.active') }}
                                </option>
                                <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>
                                    {{ __('admin.inactive') }}
                                </option>
                                </select>

                            @if($user->id === auth()->id())
                                <div class="form-text text-warning">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    {{ __('admin.cannot_change_own_status') }}
                                </div>
                                <input type="hidden" name="status" value="{{ $user->status }}">
                            @endif

                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('admin.users.index') }}" class="btn-minimal btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>{{ __('admin.cancel') }}
                        </a>
                        <button type="submit" class="btn-minimal btn-primary">
                            <i class="bi bi-check-circle me-1"></i>{{ __('admin.update') }} {{ __('admin.user') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- User Info Card -->
        <div class="card-minimal mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person-circle me-2"></i>{{ __('admin.current_information') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">{{ __('admin.user_role') }}:</span>
                        @if($user->role == 'admin')
                                <span class="badge bg-danger">{{ __('admin.roles.admin') }}</span>
                        @elseif($user->role == 'moderator')
                                <span class="badge bg-warning">{{ __('admin.roles.moderator') }}</span>
                        @else
                                <span class="badge bg-secondary">{{ __('admin.roles.user') }}</span>
                        @endif
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">{{ __('admin.user_status') }}:</span>
                        @if($user->status == 'active')
                        <span class="badge bg-success">{{ __('admin.active') }}</span>
                        @else
                        <span class="badge bg-secondary">{{ __('admin.inactive') }}</span>
                        @endif
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">{{ __('admin.joined') }}:</span>
                            <small class="text-gray-600">{{ $user->created_at->translatedFormat('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">{{ __('admin.last_updated') }}:</span>
                            <small class="text-gray-600">{{ $user->updated_at->translatedFormat('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="card-minimal mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>{{ __('admin.statistics') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary">{{ $user->posts->count() }}</h4>
                        <small class="text-muted">{{ __('admin.posts') }}</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-info">{{ $user->monuments->count() }}</h4>
                        <small class="text-muted">{{ __('admin.monuments') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="card-minimal mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-lightning me-2"></i>{{ __('admin.quick_actions') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.show', $user) }}" class="btn-minimal btn-outline-info">
                        <i class="bi bi-eye me-2"></i>{{ __('admin.view') }} {{ __('admin.details') }}
                    </a>

                    <a href="mailto:{{ $user->email }}" class="btn-minimal btn-outline-primary">
                        <i class="bi bi-envelope me-2"></i>{{ __('admin.send_email') }}
                    </a>

                    @if($user->posts->count() > 0)
                    <a href="{{ route('admin.posts.index') }}?search={{ urlencode($user->email) }}"
                        class="btn-minimal btn-outline-secondary">
                        <i class="bi bi-file-text me-2"></i>{{ __('admin.user_posts') }}
                    </a>
                    @endif

                    @if($user->monuments->count() > 0)
                    <a href="{{ route('admin.monuments.index') }}?search={{ urlencode($user->email) }}"
                        class="btn-minimal btn-outline-secondary">
                        <i class="bi bi-building me-2"></i>{{ __('admin.user_monuments') }}
                    </a>
                    @endif

                    @if($user->id !== auth()->id())
                        <hr class="my-3">
                    <form action="{{ route('admin.users.destroy', $user) }}"
                        method="POST"
                        onsubmit="return confirm('{{ __('admin.confirm_delete_user') }}')">
                        @csrf
                        @method('DELETE')
                            <button type="submit" class="btn-minimal btn-danger w-100">
                                <i class="bi bi-trash me-2"></i>{{ __('admin.delete') }} {{ __('admin.user') }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Important Notes Card -->
        <div class="card-minimal">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ __('admin.important_notes') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info border-0 mb-3">
                    @if($user->id === auth()->id())
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>{{ __('admin.editing_own_account') }}</strong>
                    @else
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>{{ __('admin.editing_other_user') }}</strong>
                    @endif
                </div>
                
                <ul class="list-unstyled small mb-0">
                    <li class="mb-2">
                        <i class="bi bi-shield-check text-warning me-2"></i>
                        {{ __('admin.role_changes_affect_permissions') }}
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-key text-warning me-2"></i>
                        {{ __('admin.password_changes_require_confirmation') }}
                    </li>
                    <li class="mb-0">
                        <i class="bi bi-person-x text-danger me-2"></i>
                        {{ __('admin.inactive_users_cannot_login') }}
                    </li>
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