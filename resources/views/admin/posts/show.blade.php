@extends('layouts.admin')

@section('title', $post->title ?? $post->title_vi)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <div>
            <h1 class="h2 mb-1">{{ __('admin.post_details') }}</h1>
            <p class="text-muted mb-0">{{ Str::limit($post->title ?? $post->title_vi, 80) }}</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-modern-primary">
                    <i class="bi bi-pencil me-1"></i>{{ __('admin.edit') }}
                </a>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-modern-secondary">
                    <i class="bi bi-arrow-left me-1"></i>{{ __('admin.back') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Post Header -->
            <div class="modern-card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h2 class="mb-2">{{ $post->getTitle('en') ?: $post->getTitle('vi') }}</h2>
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <span class="badge bg-{{ $post->status === 'approved' ? 'success' : ($post->status === 'pending' ? 'warning' : ($post->status === 'rejected' ? 'danger' : 'secondary')) }}">
                                    {{ __('admin.' . $post->status) }}
                                </span>
                                @if($post->status === 'rejected' && $post->rejection_reason)
                                    <span class="badge bg-danger" title="{{ __('admin.rejection_reason') }}: {{ $post->rejection_reason }}">
                                        <i class="bi bi-exclamation-triangle me-1"></i>{{ Str::limit($post->rejection_reason, 20) }}
                                    </span>
                                @endif
                                <span class="text-muted">
                                    <i class="bi bi-person me-1"></i>{{ $post->creator->name }}
                                </span>
                                <span class="text-muted">
                                    <i class="bi bi-calendar me-1"></i>{{ $post->created_at->locale(app()->getLocale())->translatedFormat('M d, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Featured Image -->
            @if($post->image)
            <div class="modern-card mb-4">
                <div class="card-body p-0">
                    <img src="{{ $post->image_url }}" 
                         alt="{{ $post->title ?? $post->title_vi }}" 
                         class="img-fluid rounded w-100" 
                         style="max-height: 400px; object-fit: cover;">
                </div>
            </div>
            @endif

            <!-- Language Tabs -->
            <div class="modern-card">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="contentTabs" role="tablist">
                        @if($post->translation('en'))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="en-tab" data-bs-toggle="tab" data-bs-target="#en-content" type="button" role="tab">
                                <i class="bi bi-flag me-1"></i>{{ __('admin.english') }}
                            </button>
                        </li>
                        @endif
                        @if($post->translation('vi'))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ !$post->translation('en') ? 'active' : '' }}" id="vi-tab" data-bs-toggle="tab" data-bs-target="#vi-content" type="button" role="tab">
                                <i class="bi bi-flag me-1"></i>{{ __('admin.vietnamese') }}
                            </button>
                        </li>
                        @endif
                    </ul>

                    <div class="tab-content mt-4" id="contentTabContent">
                        @if($post->translation('en'))
                        <div class="tab-pane fade show active" id="en-content" role="tabpanel">
                            @if($post->getDescription('en'))
                            <div class="mb-4">
                                <h4 class="mb-3">
                                    <i class="bi bi-info-circle text-primary me-2"></i>{{ __('admin.overview') }}
                                </h4>
                                <p class="lead text-muted">{{ $post->getDescription('en') }}</p>
                            </div>
                            @endif

                            <div>
                                <h4 class="mb-3">
                                    <i class="bi bi-file-text text-primary me-2"></i>{{ __('admin.article_content') }}
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
                                    <i class="bi bi-info-circle text-primary me-2"></i>{{ __('admin.overview') }}
                                </h4>
                                <p class="lead text-muted">{{ $post->getDescription('vi') }}</p>
                            </div>
                            @endif

                            <div>
                                <h4 class="mb-3">
                                    <i class="bi bi-file-text text-primary me-2"></i>{{ __('admin.article_content') }}
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
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Post Information -->
            <div class="modern-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle text-primary me-2"></i>{{ __('admin.post_information') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <span class="info-label">{{ __('admin.languages') }}:</span>
                        <div>
                            @if($post->translation('en'))
                                <span class="badge bg-primary me-1">EN</span>
                            @endif
                            @if($post->translation('vi'))
                                <span class="badge bg-success">VI</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">{{ __('admin.status') }}:</span>
                        <span class="badge bg-{{ $post->status === 'approved' ? 'success' : ($post->status === 'pending' ? 'warning' : 'secondary') }}">
                            {{ __('admin.' . $post->status) }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">{{ __('admin.created_by') }}:</span>
                        <span class="info-value">{{ $post->creator->name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">{{ __('admin.created') }}:</span>
                        <span class="info-value">{{ $post->created_at->locale(app()->getLocale())->translatedFormat('M d, Y H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">{{ __('admin.last_updated') }}:</span>
                        <span class="info-value">{{ $post->updated_at->locale(app()->getLocale())->translatedFormat('M d, Y H:i') }}</span>
                    </div>
                    @if($post->published_at)
                    <div class="info-item">
                        <span class="info-label">{{ __('admin.published') }}:</span>
                        <span class="info-value">{{ $post->published_at->format('M d, Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="modern-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning text-warning me-2"></i>{{ __('admin.quick_actions') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-modern-primary">
                            <i class="bi bi-pencil me-2"></i>{{ __('admin.edit') }} {{ __('admin.post') }}
                        </a>

                        @if($post->status !== 'approved' && auth()->user()->isAdmin())
                            <form action="{{ route('admin.posts.approve', $post) }}" method="POST" class="d-grid">
                                @csrf
                                <button type="submit" class="btn btn-modern-success">
                                    <i class="bi bi-check me-2"></i>{{ __('admin.approve') }} {{ __('admin.post') }}
                                </button>
                            </form>
                        @endif

                        @if($post->status === 'pending' && auth()->user()->isAdmin())
                            <button type="button" class="btn btn-modern-warning" onclick="openRejectModal('{{ route('admin.posts.reject', $post) }}')">
                                <i class="bi bi-x-circle me-2"></i>{{ __('admin.reject') }} {{ __('admin.post') }}
                            </button>
                        @endif

                        <a href="{{ route('admin.posts.index') }}" class="btn btn-modern-secondary">
                            <i class="bi bi-arrow-left me-2"></i>{{ __('admin.back_to_posts') }}
                        </a>

                        @if(auth()->user()->isAdmin() || ($post->status !== 'approved' && auth()->user()->isModerator()))
                            <button type="button" class="btn btn-modern-danger" onclick="openDeleteModal('{{ route('admin.posts.destroy', $post) }}')">
                                <i class="bi bi-trash me-2"></i>{{ __('admin.delete') }} {{ __('admin.post') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.components.reject-modal')
@include('admin.components.delete-modal')
@endsection

@push('styles')
<style>
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
