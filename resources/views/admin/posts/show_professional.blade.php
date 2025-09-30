@extends('layouts.admin')

@section('title', $post->title ?? $post->title_vi)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h3 mb-1">Post Details</h1>
        <p class="text-muted mb-0">{{ Str::limit($post->title ?? $post->title_vi, 80) }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.posts.edit', $post) }}" class="btn-minimal btn-primary">
            <i class="bi bi-pencil"></i> {{ __('admin.edit') }}
        </a>
        <a href="{{ route('admin.posts.index') }}" class="btn-minimal">
            <i class="bi bi-arrow-left"></i> {{ __('admin.back') }}
        </a>
    </div>
</div>

<!-- Post Header -->
<div class="card-minimal mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h2 class="mb-2">{{ $post->getTitle('en') ?: $post->getTitle('vi') }}</h2>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-{{ $post->status === 'approved' ? 'success' : ($post->status === 'pending' ? 'warning' : 'secondary') }}">
                        {{ ucfirst($post->status) }}
                    </span>
                    <span class="text-muted">
                        <i class="bi bi-person"></i> {{ $post->creator->name }}
                    </span>
                    <span class="text-muted">
                        <i class="bi bi-calendar"></i> {{ $post->created_at->format('M d, Y') }}
                    </span>
                    <span class="text-muted">
                        <i class="bi bi-translate"></i>
                        @if($post->translation('en') && $post->translation('vi'))
                            EN + VI
                        @elseif($post->translation('en'))
                            EN only
                        @elseif($post->translation('vi'))
                            VI only
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Featured Image -->
        @if($post->image)
        <div class="card-minimal mb-4">
            <div class="card-body p-0">
                <img src="{{ $post->image_url }}"
                     alt="{{ $post->title ?? $post->title_vi }}"
                     class="img-fluid rounded w-100"
                     style="max-height: 400px; object-fit: cover;">
            </div>
        </div>
        @endif

        <!-- Language Tabs -->
        <div class="card-minimal">
            <div class="card-body">
                <ul class="nav nav-tabs" id="contentTabs" role="tablist">
                    @if($post->translation('en'))
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="en-tab" data-bs-toggle="tab" data-bs-target="#en-content" type="button" role="tab">
                            <i class="bi bi-flag"></i> English
                        </button>
                    </li>
                    @endif
                    @if($post->translation('vi'))
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ !$post->translation('en') ? 'active' : '' }}" id="vi-tab" data-bs-toggle="tab" data-bs-target="#vi-content" type="button" role="tab">
                            <i class="bi bi-flag"></i> Tiếng Việt
                        </button>
                    </li>
                    @endif
                </ul>

                <div class="tab-content mt-3" id="contentTabContent">
                    @if($post->translation('en'))
                    <div class="tab-pane fade show active" id="en-content" role="tabpanel">
                        @if($post->getDescription('en'))
                        <div class="mb-4">
                            <h4 class="mb-3">
                                <i class="bi bi-info-circle text-primary"></i> Overview
                            </h4>
                            <p class="lead text-muted">{{ $post->getDescription('en') }}</p>
                        </div>
                        @endif

                        <div>
                            <h4 class="mb-3">
                                <i class="bi bi-file-text text-primary"></i> Article Content
                            </h4>
                            <div class="content-body">
                                {!! $post->getContent('en') !!}
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($post->translation('vi'))
                    <div class="tab-pane fade {{ !$post->translation('en') ? 'show active' : '' }}" id="vi-content" role="tabpanel">
                        @if($post->getDescription('vi'))
                        <div class="mb-4">
                            <h4 class="mb-3">
                                <i class="bi bi-info-circle text-primary"></i> Tổng quan
                            </h4>
                            <p class="lead text-muted">{{ $post->getDescription('vi') }}</p>
                        </div>
                        @endif

                        <div>
                            <h4 class="mb-3">
                                <i class="bi bi-file-text text-primary"></i> Nội dung bài viết
                            </h4>
                            <div class="content-body">
                                {!! $post->getContent('vi') !!}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar - Post Information & Quick Actions -->
        <div class="col-lg-4">
            <!-- Post Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle text-primary"></i> Post Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <span class="info-label">Languages:</span>
                        <div>
                            @if($post->translation('en'))
                                <span class="status-badge bg-primary text-white me-1">EN</span>
                            @endif
                            @if($post->translation('vi'))
                                <span class="status-badge bg-success text-white">VI</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status:</span>
                        @if($post->status == 'approved')
                            <span class="status-badge bg-success text-white">Approved</span>
                        @elseif($post->status == 'pending')
                            <span class="status-badge bg-warning text-dark">Pending</span>
                        @else
                            <span class="status-badge bg-secondary text-white">Draft</span>
                        @endif
                    </div>
                    <div class="info-item">
                        <span class="info-label">Created by:</span>
                        <span class="info-value">{{ $post->creator->name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Created:</span>
                        <span class="info-value">{{ $post->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Last updated:</span>
                        <span class="info-value">{{ $post->updated_at->format('M d, Y H:i') }}</span>
                    </div>
                    @if($post->published_at)
                    <div class="info-item">
                        <span class="info-label">Published:</span>
                        <span class="info-value">{{ $post->published_at->format('M d, Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightning text-warning"></i> Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.posts.edit', $post) }}" class="elegant-btn elegant-btn-primary">
                            <i class="bi bi-pencil"></i> Edit Post
                        </a>

                        @if($post->status !== 'approved' && auth()->user()->isAdmin())
                            <form action="{{ route('admin.posts.approve', $post) }}" method="POST">
                                @csrf
                                <button type="submit" class="elegant-btn elegant-btn-success w-100">
                                    <i class="bi bi-check"></i> Approve Post
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('admin.posts.index') }}" class="elegant-btn">
                            <i class="bi bi-arrow-left"></i> Back to Posts
                        </a>

                        @if(auth()->user()->isAdmin() || ($post->status !== 'approved' && auth()->user()->isModerator()))
                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this post?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="elegant-btn elegant-btn-danger w-100">
                                    <i class="bi bi-trash"></i> Delete Post
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.content-section {
    background: #fff;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    border: 1px solid #e9ecef;
}

.post-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #6c757d;
    font-size: 0.875rem;
}

.info-value {
    color: #495057;
    font-size: 0.875rem;
}

.elegant-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    background: #fff;
    color: #495057;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.elegant-btn:hover {
    background: #f8f9fa;
    border-color: #dee2e6;
    color: #495057;
    text-decoration: none;
    transform: translateY(-1px);
}

.elegant-btn-primary {
    background: #007bff;
    border-color: #007bff;
    color: #fff;
}

.elegant-btn-primary:hover {
    background: #0056b3;
    border-color: #0056b3;
    color: #fff;
}

.elegant-btn-success {
    background: #28a745;
    border-color: #28a745;
    color: #fff;
}

.elegant-btn-success:hover {
    background: #1e7e34;
    border-color: #1e7e34;
    color: #fff;
}

.elegant-btn-danger {
    background: #dc3545;
    border-color: #dc3545;
    color: #fff;
}

.elegant-btn-danger:hover {
    background: #c82333;
    border-color: #c82333;
    color: #fff;
}

.content-body {
    line-height: 1.8;
    font-size: 1.1rem;
    color: #495057;
}

.content-body h1, .content-body h2, .content-body h3, 
.content-body h4, .content-body h5, .content-body h6 {
    color: #2c3e50;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.content-body p {
    margin-bottom: 1.5rem;
}

.content-body img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1.5rem 0;
}

.content-body blockquote {
    border-left: 4px solid #007bff;
    padding-left: 1.5rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: #6c757d;
}

.content-body ul, .content-body ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.content-body li {
    margin-bottom: 0.5rem;
}
</style>
@endpush
