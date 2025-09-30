@extends('layouts.admin')

@section('title', 'Dashboard')

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
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card primary">
            <div class="stat-icon">
                <i class="bi bi-file-text"></i>
            </div>
            <div class="d-flex justify-content-between align-items-end">
                <div>
                    <h3 class="mb-0 fw-bold">{{ $stats['total_posts'] }}</h3>
                    <p class="text-muted mb-0 small">{{ __('admin.total_posts') }}</p>
                </div>
                <div class="text-success small">
                    <i class="bi bi-arrow-up"></i> +12%
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="bi bi-clock"></i>
            </div>
            <div class="d-flex justify-content-between align-items-end">
                <div>
                    <h3 class="mb-0 fw-bold">{{ $stats['pending_posts'] }}</h3>
                    <p class="text-muted mb-0 small">Pending Posts</p>
                </div>
                <div class="text-warning small">
                    <i class="bi bi-dash"></i> 0%
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card success">
            <div class="stat-icon">
                <i class="bi bi-building"></i>
            </div>
            <div class="d-flex justify-content-between align-items-end">
                <div>
                    <h3 class="mb-0 fw-bold">{{ $stats['total_monuments'] }}</h3>
                    <p class="text-muted mb-0 small">{{ __('admin.total_monuments') }}</p>
                </div>
                <div class="text-success small">
                    <i class="bi bi-arrow-up"></i> +8%
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card danger">
            <div class="stat-icon">
                <i class="bi bi-people"></i>
            </div>
            <div class="d-flex justify-content-between align-items-end">
                <div>
                    <h3 class="mb-0 fw-bold">{{ $stats['total_users'] }}</h3>
                    <p class="text-muted mb-0 small">{{ __('admin.total_users') }}</p>
                </div>
                <div class="text-success small">
                    <i class="bi bi-arrow-up"></i> +5%
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-5">
    <div class="col-12">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.posts.create') }}" class="btn btn-modern-primary w-100 py-3">
                            <i class="bi bi-plus-circle fs-4 d-block mb-2"></i>
                            <small>Create New Post</small>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.monuments.create') }}" class="btn btn-modern-secondary w-100 py-3">
                            <i class="bi bi-plus-circle fs-4 d-block mb-2"></i>
                            <small>Create New Monument</small>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.gallery.index') }}" class="btn btn-modern-secondary w-100 py-3">
                            <i class="bi bi-images fs-4 d-block mb-2"></i>
                            <small>Manage Gallery</small>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-modern-secondary w-100 py-3">
                            <i class="bi bi-people fs-4 d-block mb-2"></i>
                            <small>Manage Users</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Recent Posts</h5>
            </div>
            <div class="card-body">
                @if($recentPosts->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentPosts as $post)
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
                            <i class="bi bi-plus-lg me-2"></i>Create Post
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-building me-2"></i>Recent Monuments</h5>
            </div>
            <div class="card-body">
                @if($recentMonuments->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentMonuments as $monument)
                        <div class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 fw-semibold">{{ $monument->title }}</h6>
                                    <small class="text-muted">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $monument->zone }}
                                    </small>
                                </div>
                                <small class="text-muted">{{ $monument->created_at->format('M d') }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.monuments.index') }}" class="btn btn-modern-secondary btn-sm">
                            View All <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-building text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-2 small">{{ __('admin.no_monuments_yet') }}</p>
                        <a href="{{ route('admin.monuments.create') }}" class="btn btn-modern-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i>Create Monument
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
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
        greeting = '🌅 Good Morning';
    } else if (hour >= 12 && hour < 17) {
        greeting = '☀️ Good Afternoon';
    } else if (hour >= 17 && hour < 21) {
        greeting = '🌆 Good Evening';
    } else {
        greeting = '🌙 Good Night';
    }

    document.getElementById('current-time').textContent = timeString;
    document.getElementById('time-greeting').textContent = greeting;
}

// Update clock immediately and then every second
updateClock();
setInterval(updateClock, 1000);
</script>
@endpush
