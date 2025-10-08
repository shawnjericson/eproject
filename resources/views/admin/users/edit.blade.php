@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ __('admin.edit') }} User: {{ $user->name }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-info me-2">
            <i class="bi bi-eye"></i> {{ __('admin.view') }}
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> {{ __('admin.back') }} to Users
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Full {{ __('admin.name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $user->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('admin.email') }} Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $user->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Leave empty to keep current password</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation">
                                <div class="form-text">Required only if changing password</div>
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
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="moderator" {{ old('role', $user->role) == 'moderator' ? 'selected' : '' }}>Moderator</option>
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
                                    <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>{{ __('admin.active') }}</option>
                                    <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Update User</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">{{ __('admin.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>User Information</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><strong>Current {{ __('admin.name') }}:</strong> {{ $user->name }}</li>
                    <li><strong>Current {{ __('admin.email') }}:</strong> {{ $user->email }}</li>
                    <li><strong>Current {{ __('admin.role') }}:</strong> 
                        @if($user->role == 'admin')
                            <span class="badge bg-danger">Admin</span>
                        @elseif($user->role == 'moderator')
                            <span class="badge bg-warning">Moderator</span>
                        @else
                            <span class="badge bg-secondary">User</span>
                        @endif
                    </li>
                    <li><strong>Current {{ __('admin.status') }}:</strong> 
                        @if($user->status == 'active')
                            <span class="badge bg-success">{{ __('admin.active') }}</span>
                        @else
                            <span class="badge bg-secondary">{{ __('admin.inactive') }}</span>
                        @endif
                    </li>
                    <li><strong>Joined:</strong> {{ $user->created_at->format('M d, Y H:i') }}</li>
                    <li><strong>Last updated:</strong> {{ $user->updated_at->format('M d, Y H:i') }}</li>
                </ul>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5>User Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary">{{ $user->posts->count() }}</h4>
                        <small class="text-muted">Posts</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-info">{{ $user->monuments->count() }}</h4>
                        <small class="text-muted">Monuments</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5>Quick {{ __('admin.actions') }}</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-info">
                        <i class="bi bi-eye"></i> {{ __('admin.view') }} User Details
                    </a>
                    
                    <a href="mailto:{{ $user->email }}" class="btn btn-outline-primary">
                        <i class="bi bi-envelope"></i> Send {{ __('admin.email') }}
                    </a>
                    
                    @if($user->posts->count() > 0)
                        <a href="{{ route('admin.posts.index') }}?search={{ urlencode($user->email) }}" 
                           class="btn btn-outline-secondary">
                            <i class="bi bi-file-text"></i> {{ __('admin.view') }} User Posts
                        </a>
                    @endif
                    
                    @if($user->monuments->count() > 0)
                        <a href="{{ route('admin.monuments.index') }}?search={{ urlencode($user->email) }}" 
                           class="btn btn-outline-secondary">
                            <i class="bi bi-building"></i> {{ __('admin.view') }} User Monuments
                        </a>
                    @endif
                    
                    @if($user->id !== auth()->id())
                        <hr>
                        <form action="{{ route('admin.users.destroy', $user) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this user? This will also delete all their content.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-trash"></i> {{ __('admin.delete') }} User
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5>Important Notes</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled small">
                    @if($user->id === auth()->id())
                        <li><i class="bi bi-info-circle text-info"></i> You are editing your own account</li>
                    @endif
                    <li><i class="bi bi-shield-check text-warning"></i> {{ __('admin.role') }} changes affect system permissions</li>
                    <li><i class="bi bi-key text-warning"></i> Password changes require confirmation</li>
                    <li><i class="bi bi-person-x text-danger"></i> {{ __('admin.inactive') }} users cannot login</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    
    function validatePassword() {
        if (passwordInput.value && confirmPasswordInput.value && passwordInput.value !== confirmPasswordInput.value) {
            confirmPasswordInput.setCustomValidity('Passwords do not match');
        } else {
            confirmPasswordInput.setCustomValidity('');
        }
    }
    
    passwordInput.addEventListener('input', validatePassword);
    confirmPasswordInput.addEventListener('input', validatePassword);
    
    // Keep UX hint without toggling 'required' attribute
    passwordInput.addEventListener('input', function() {
        const hint = confirmPasswordInput.parentElement.querySelector('.form-text');
        if (this.value) {
            if (hint) hint.textContent = 'Required when changing password';
        } else {
            if (hint) hint.textContent = 'Required only if changing password';
        }
    });
});
</script>
@endpush
@endsection
