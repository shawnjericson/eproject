@extends('layouts.admin')

@section('title', __('admin.dashboard'))

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-2">{{ __('admin.welcome_back') }}, {{ auth()->user()?->name ?? 'Admin' }}! 👋</h1>
            <p class="text-muted mb-0">{{ __('admin.dashboard_subtitle') }}</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="text-center">
                <div id="current-time" class="h4 mb-0 text-primary"></div>
                <div id="time-greeting" class="small text-muted"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modern Stats Cards -->

<div class="row mb-5">
    <div class="{{ $isAdmin ? 'col-xl-3' : 'col-xl-4' }} col-md-6 mb-4">
        <a href="{{ route('admin.monuments.index') }}" class="text-decoration-none">
            <div class="stat-card success clickable-card">
                <div class="stat-icon">
                    <i class="bi bi-building"></i>
                </div>
                <div class="d-flex justify-content-between align-items-end">
                    <div>
                        <h3 class="mb-0 fw-bold">{{ $stats['total_monuments'] }}</h3>
                        <p class="text-muted mb-0 small">{{ __('admin.total_monuments') }}</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="{{ $isAdmin ? 'col-xl-3' : 'col-xl-4' }} col-md-6 mb-4">
        <a href="{{ route('admin.posts.index') }}" class="text-decoration-none">
            <div class="stat-card primary clickable-card">
                <div class="stat-icon">
                    <i class="bi bi-file-text"></i>
                </div>
                <div class="d-flex justify-content-between align-items-end">
                    <div>
                        <h3 class="mb-0 fw-bold">{{ $stats['total_posts'] }}</h3>
                        <p class="text-muted mb-0 small">{{ __('admin.total_posts') }}</p>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="{{ $isAdmin ? 'col-xl-3' : 'col-xl-4' }} col-md-6 mb-4">
        <a href="{{ route('admin.posts.index', ['status' => 'pending']) }}" class="text-decoration-none">
            <div class="stat-card warning clickable-card">
                <div class="stat-icon">
                    <i class="bi bi-clock"></i>
                </div>
                <div class="d-flex justify-content-between align-items-end">
                    <div>
                        <h3 class="mb-0 fw-bold">{{ $stats['pending_posts'] }}</h3>
                        <p class="text-muted mb-0 small">{{ __('admin.pending_posts') }}</p>
                    </div>
                </div>
            </div>
        </a>
    </div>

    @if($isAdmin)
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
            <div class="stat-card danger clickable-card">
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="d-flex justify-content-between align-items-end">
                    <div>
                        <h3 class="mb-0 fw-bold">{{ $stats['total_users'] }}</h3>
                        <p class="text-muted mb-0 small">{{ __('admin.total_users') }}</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
    @endif
</div>

<!-- Quick Actions -->
<div class="row mb-5">
    <div class="col-12">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>{{ __('admin.quick_actions') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.monuments.create') }}" class="btn btn-modern-primary w-100 py-3">
                            <i class="bi bi-plus-circle fs-4 d-block mb-2"></i>
                            <small>{{ __('admin.create_new_monument') }}</small>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.posts.create') }}" class="btn btn-modern-secondary w-100 py-3">
                            <i class="bi bi-plus-circle fs-4 d-block mb-2"></i>
                            <small>{{ __('admin.create_new_post') }}</small>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.gallery.index') }}" class="btn btn-modern-secondary w-100 py-3">
                            <i class="bi bi-images fs-4 d-block mb-2"></i>
                            <small>{{ __('admin.gallery_management') }}</small>
                        </a>
                    </div>
                    @if($isAdmin)
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-modern-secondary w-100 py-3">
                            <i class="bi bi-people fs-4 d-block mb-2"></i>
                            <small>{{ __('admin.users_management') }}</small>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<!-- <div class="row">
    <div class="col-lg-8 mb-4">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Recent Posts</h5>
            </div>
            <div class="card-body">
                @if($recent_posts->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recent_posts as $post)
                        <div class="list-group-item border-0 px-0 py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-semibold">{{ $post->title }}</h6>
                                    <p class="mb-1 text-muted small">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar me-1"></i>{{ $post->created_at->format('M d, Y') }}
                                        <i class="bi bi-tag ms-3 me-1"></i>{{ ucfirst($post->status) }}
                                    </small>
                                </div>
                                <span class="badge bg-{{ $post->status == 'approved' ? 'success' : ($post->status == 'pending' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($post->status) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-modern-secondary">
                            View All Posts <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-file-text text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">{{ __('admin.no_posts_yet') }} {{ __('admin.create_first_post') }}</p>
                        <a href="{{ route('admin.posts.create') }}" class="btn btn-modern-primary">
                            <i class="bi bi-plus-lg me-2"></i>{{ __('admin.create_post') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-building me-2"></i>{{ __('admin.recent_monuments') }}</h5>
            </div>
            <div class="card-body">
                @if($recent_monuments->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recent_monuments as $monument)
                        <div class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 fw-semibold">{{ $monument->title }}</h6>
                                    <small class="text-muted">
                                        <i class="bi bi-geo-alt me-1"></i>{{ __('admin.zones.' . strtolower($monument->zone)) }}
                                    </small>
                                </div>
                                <small class="text-muted">{{ $monument->created_at->locale(app()->getLocale())->translatedFormat('M d') }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.monuments.index') }}" class="btn btn-modern-secondary btn-sm">
                            {{ __('admin.view_all') }} <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-building text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-2 small">{{ __('admin.no_monuments_yet') }}</p>
                        <a href="{{ route('admin.monuments.create') }}" class="btn btn-modern-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i>{{ __('admin.create_monument') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div> -->
@endsection

@push('scripts')
<script>
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', {
            hour12: false,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });

        const hour = now.getHours();
        let greeting = '';

        if (hour >= 5 && hour < 12) {
            greeting = "🌅 {{ __('admin.good_morning') }}";
        } else if (hour >= 12 && hour < 17) {
            greeting = "☀️ {{ __('admin.good_afternoon') }}";
        } else if (hour >= 17 && hour < 21) {
            greeting = "🌆 {{ __('admin.good_evening') }}";
        } else {
            greeting = "🌙 {{ __('admin.good_night') }}";
        }

        document.getElementById('current-time').textContent = timeString;
        document.getElementById('time-greeting').textContent = greeting;
    }

    // Update clock immediately and then every second
    updateClock();
    setInterval(updateClock, 1000);
</script>
@endpush