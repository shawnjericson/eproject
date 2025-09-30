@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-2">{{ __('admin.welcome_back') }}, {{ auth()->user()->name }}! 👋</h1>
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

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{ __('admin.total_posts') }}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_posts'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-file-text fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">{{ __('admin.pending') }} {{ __('admin.posts') }}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_posts'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{ __('admin.total_monuments') }}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_monuments'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-building fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Feedbacks</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_feedbacks'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-chat-dots fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- content Row -->
<div class="row">
    <!-- Recent Posts -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Recent Posts</h6>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-sm btn-primary">{{ __('admin.view') }} All</a>
            </div>
            <div class="card-body">
                @forelse($recent_posts as $post)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ Str::limit($post->title, 50) }}</h6>
                            <small class="text-muted">
                                By {{ $post->creator->name }} • {{ $post->created_at->diffForHumans() }}
                                <span class="badge bg-{{ $post->status === 'approved' ? 'success' : ($post->status === 'pending' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($post->status) }}
                                </span>
                            </small>
                        </div>
                        <a href="{{ route('admin.posts.show', $post) }}" class="btn btn-sm btn-outline-primary">{{ __('admin.view') }}</a>
                    </div>
                @empty
                    <p class="text-muted">{{ __('admin.no_posts_yet') }}</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Feedbacks -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Recent Feedbacks</h6>
                <a href="{{ route('admin.feedbacks.index') }}" class="btn btn-sm btn-primary">{{ __('admin.view') }} All</a>
            </div>
            <div class="card-body">
                @forelse($recent_feedbacks as $feedback)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $feedback->name }}</h6>
                            <p class="mb-1 small">{{ Str::limit($feedback->message, 80) }}</p>
                            <small class="text-muted">
                                {{ $feedback->created_at->diffForHumans() }}
                                @if($feedback->monument)
                                    • {{ $feedback->monument->title }}
                                @endif
                            </small>
                        </div>
                        <a href="{{ route('admin.feedbacks.show', $feedback) }}" class="btn btn-sm btn-outline-primary">{{ __('admin.view') }}</a>
                    </div>
                @empty
                    <p class="text-muted">{{ __('admin.no_feedbacks_yet') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-lightning"></i> Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary w-100 py-3">
                            <i class="bi bi-plus-circle fs-4"></i><br>
                            <small>Create New Post</small>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.monuments.create') }}" class="btn btn-success w-100 py-3">
                            <i class="bi bi-plus-circle fs-4"></i><br>
                            <small>Create New Monument</small>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.gallery.index') }}" class="btn btn-info w-100 py-3">
                            <i class="bi bi-images fs-4"></i><br>
                            <small>Manage Gallery</small>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.feedbacks.index') }}" class="btn btn-warning w-100 py-3">
                            <i class="bi bi-chat-dots fs-4"></i><br>
                            <small>View Feedbacks</small>
                        </a>
                    </div>
                </div>
                @if(auth()->user()->role === 'admin')
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary w-100 py-3">
                            <i class="bi bi-people fs-4"></i><br>
                            <small>Manage Users</small>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-dark w-100 py-3">
                            <i class="bi bi-gear fs-4"></i><br>
                            <small>Site Settings</small>
                        </a>
                    </div>

                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
#current-time {
    font-family: 'Courier New', monospace;
    font-weight: bold;
}
</style>
@endpush

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
    let icon = '';

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
@endsection
