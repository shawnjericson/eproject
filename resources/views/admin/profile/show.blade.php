@extends('layouts.admin')

@section('title', __('admin.my_profile'))

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">{{ __('admin.my_profile') }}</h1>
            <p class="text-muted mb-0">{{ __('admin.profile_information') }}</p>
        </div>
        <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> {{ __('admin.edit_profile') }}
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <!-- Avatar -->
                    <div class="mb-3">
                        <img src="{{ $user->avatar_url }}" 
                             alt="{{ $user->name }}" 
                             class="rounded-circle mb-3"
                             style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #f8f9fa;">
                    </div>

                    <!-- Name & Role -->
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">
                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </p>

                    <!-- Profile Completion -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">{{ __('admin.profile_completion') }}</small>
                            <small class="fw-bold">{{ $user->profile_completion }}%</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" 
                                 role="progressbar" 
                                 style="width: {{ $user->profile_completion }}%"
                                 aria-valuenow="{{ $user->profile_completion }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        @if($user->profile_completion < 100)
                            <small class="text-muted d-block mt-2">
                                {{ __('admin.profile_completion_hint') }}
                            </small>
                        @endif
                    </div>

                    <!-- Stats -->
                    <div class="row text-center mt-4">
                        <div class="col-6 border-end">
                            <h5 class="mb-0">{{ $user->posts()->count() }}</h5>
                            <small class="text-muted">{{ __('admin.posts') }}</small>
                        </div>
                        <div class="col-6">
                            <h5 class="mb-0">{{ $user->monuments()->count() }}</h5>
                            <small class="text-muted">{{ __('admin.monuments') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Info Card -->
            <div class="card shadow mt-4">
                <div class="card-body">
                    <h6 class="card-title mb-3">{{ __('admin.account_information') }}</h6>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">{{ __('admin.status') }}</small>
                        <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">{{ __('admin.member_since') }}</small>
                        <strong>{{ $user->created_at->format('M d, Y') }}</strong>
                    </div>

                    <div>
                        <small class="text-muted d-block">{{ __('admin.last_updated') }}</small>
                        <strong>{{ $user->updated_at->diffForHumans() }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">{{ __('admin.personal_information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Full Name -->
                        <div class="col-md-6 mb-4">
                            <label class="text-muted small mb-1">{{ __('admin.full_name') }}</label>
                            <p class="mb-0 fw-bold">{{ $user->name }}</p>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-4">
                            <label class="text-muted small mb-1">{{ __('admin.email_address') }}</label>
                            <p class="mb-0 fw-bold">{{ $user->email }}</p>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6 mb-4">
                            <label class="text-muted small mb-1">{{ __('admin.phone_number') }}</label>
                            <p class="mb-0 fw-bold">
                                {{ $user->phone ?? '-' }}
                            </p>
                        </div>

                        <!-- Date of Birth -->
                        <div class="col-md-6 mb-4">
                            <label class="text-muted small mb-1">{{ __('admin.date_of_birth') }}</label>
                            <p class="mb-0 fw-bold">
                                @if($user->date_of_birth)
                                    {{ $user->date_of_birth->format('M d, Y') }}
                                    <span class="text-muted">({{ $user->age }} {{ __('admin.years_old') }})</span>
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <!-- Address -->
                        <div class="col-12 mb-4">
                            <label class="text-muted small mb-1">{{ __('admin.address') }}</label>
                            <p class="mb-0 fw-bold">
                                {{ $user->address ?? '-' }}
                            </p>
                        </div>

                        <!-- Bio -->
                        <div class="col-12">
                            <label class="text-muted small mb-1">{{ __('admin.bio') }}</label>
                            <p class="mb-0">
                                {{ $user->bio ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Card -->
            <div class="card shadow mt-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">{{ __('admin.security') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">{{ __('admin.password') }}</h6>
                            <p class="text-muted mb-0 small">{{ __('admin.change_password') }}</p>
                        </div>
                        <a href="{{ route('admin.profile.edit') }}#password" class="btn btn-outline-primary btn-sm">
                            {{ __('admin.change_password') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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
    
    .progress {
        border-radius: 10px;
        background-color: #e9ecef;
    }
    
    .progress-bar {
        border-radius: 10px;
    }
</style>
@endpush

