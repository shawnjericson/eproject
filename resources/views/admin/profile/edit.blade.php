@extends('layouts.admin')

@section('title', __('admin.edit_profile'))

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">{{ __('admin.edit_profile') }}</h1>
            <p class="text-muted mb-0">{{ __('admin.update_profile') }}</p>
        </div>
        <a href="{{ route('admin.profile.show') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> {{ __('admin.back_to_profile') }}
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Avatar Upload -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">{{ __('admin.profile_picture') }}</h5>
                </div>
                <div class="card-body text-center">
                    <!-- Current Avatar -->
                    <div class="mb-3">
                        <img src="{{ $user->avatar_url }}" 
                             alt="{{ $user->name }}" 
                             id="avatar-preview"
                             class="rounded-circle mb-3"
                             style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #f8f9fa;">
                    </div>

                    <!-- Upload Form -->
                    <form action="{{ route('admin.profile.avatar.update') }}" method="POST" enctype="multipart/form-data" id="avatar-form">
                        @csrf
                        <div class="mb-3">
                            <input type="file" 
                                   class="form-control @error('avatar') is-invalid @enderror" 
                                   id="avatar" 
                                   name="avatar" 
                                   accept="image/*"
                                   onchange="previewAvatar(this)">
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-upload"></i> {{ __('admin.upload_avatar') }}
                        </button>
                    </form>

                    @if($user->avatar)
                        <form action="{{ route('admin.profile.avatar.delete') }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('{{ __('admin.confirm_delete') }}')">
                                <i class="bi bi-trash"></i> {{ __('admin.remove_avatar') }}
                            </button>
                        </form>
                    @endif

                    <!-- Requirements -->
                    <div class="mt-3 text-start">
                        <small class="text-muted">
                            <strong>{{ __('admin.avatar_requirements') }}:</strong><br>
                            {{ __('admin.avatar_requirements_text') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="card shadow mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">{{ __('admin.personal_information') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Full Name -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">{{ __('admin.full_name') }} <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}"
                                       placeholder="{{ __('admin.enter_full_name') }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">{{ __('admin.email_address') }} <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}"
                                       placeholder="{{ __('admin.enter_email') }}"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">{{ __('admin.phone_number') }}</label>
                                <input type="tel" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $user->phone) }}"
                                       placeholder="{{ __('admin.enter_phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date of Birth -->
                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">{{ __('admin.date_of_birth') }}</label>
                                <input type="date" 
                                       class="form-control @error('date_of_birth') is-invalid @enderror" 
                                       id="date_of_birth" 
                                       name="date_of_birth" 
                                       value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}"
                                       max="{{ date('Y-m-d') }}">
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="col-12 mb-3">
                                <label for="address" class="form-label">{{ __('admin.address') }}</label>
                                <input type="text" 
                                       class="form-control @error('address') is-invalid @enderror" 
                                       id="address" 
                                       name="address" 
                                       value="{{ old('address', $user->address) }}"
                                       placeholder="{{ __('admin.enter_address') }}">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Bio -->
                            <div class="col-12 mb-3">
                                <label for="bio" class="form-label">{{ __('admin.bio') }}</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" 
                                          id="bio" 
                                          name="bio" 
                                          rows="4"
                                          placeholder="{{ __('admin.enter_bio') }}"
                                          maxlength="1000">{{ old('bio', $user->bio) }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">{{ __('admin.characters_remaining') }}: <span id="bio-count">{{ 1000 - strlen($user->bio ?? '') }}</span></small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.profile.show') }}" class="btn btn-outline-secondary">
                                {{ __('admin.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> {{ __('admin.save_changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="card shadow" id="password">
                <div class="card-header bg-white">
                    <h5 class="mb-0">{{ __('admin.change_password') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Current Password -->
                            <div class="col-12 mb-3">
                                <label for="current_password" class="form-label">{{ __('admin.current_password') }} <span class="text-danger">*</span></label>
                                <input type="password" 
                                       class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" 
                                       name="current_password"
                                       placeholder="{{ __('admin.enter_current_password') }}"
                                       required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div class="col-md-6 mb-3">
                                <label for="new_password" class="form-label">{{ __('admin.new_password') }} <span class="text-danger">*</span></label>
                                <input type="password" 
                                       class="form-control @error('new_password') is-invalid @enderror" 
                                       id="new_password" 
                                       name="new_password"
                                       placeholder="{{ __('admin.enter_new_password') }}"
                                       required>
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-md-6 mb-3">
                                <label for="new_password_confirmation" class="form-label">{{ __('admin.confirm_new_password') }} <span class="text-danger">*</span></label>
                                <input type="password" 
                                       class="form-control" 
                                       id="new_password_confirmation" 
                                       name="new_password_confirmation"
                                       placeholder="{{ __('admin.confirm_password') }}"
                                       required>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <small>
                                <strong>{{ __('admin.password_requirements') }}:</strong><br>
                                {{ __('admin.password_requirements_text') }}
                            </small>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-shield-lock"></i> {{ __('admin.update_password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Avatar preview
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Bio character counter
    document.getElementById('bio')?.addEventListener('input', function() {
        const remaining = 1000 - this.value.length;
        document.getElementById('bio-count').textContent = remaining;
    });
</script>
@endpush

@push('styles')
<style>
    .card {
        border: none;
        border-radius: 12px;
    }
    
    .card-header {
        border-bottom: 1px solid #e9ecef;
        padding: 1.25rem;
    }
    
    .form-label {
        font-weight: 500;
        color: #495057;
    }
    
    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
</style>
@endpush

