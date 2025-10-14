@extends('layouts.admin')

@section('title', __('admin.posts_management'))

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ __('admin.posts_management') }}</h1>
        <p class="text-muted mb-0">{{ __('admin.manage_all_posts_articles') }}</p>
    </div>
    <a href="{{ route('admin.posts.create') }}" class="btn-minimal btn-primary">
        {{ __('admin.create_new_post') }}
    </a>
</div>

<!-- Filters -->
<div class="card-minimal mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.posts.index') }}" id="filterForm">
            <div class="row g-3">
                <div class="col-md-2">
                    <select name="status" class="form-select auto-filter">
                        <option value="">{{ __('admin.all_status') }}</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>{{ __('admin.draft') }}</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('admin.pending') }}</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ __('admin.approved') }}</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ __('admin.rejected') }}</option>
                    </select>
                </div>
                @if(auth()->user()->isAdmin())
                <div class="col-md-2">
                    <select name="author" class="form-select auto-filter">
                        <option value="">{{ __('admin.all_authors') }}</option>
                        @foreach(\App\Models\User::whereIn('role', ['admin', 'moderator'])->get() as $user)
                            <option value="{{ $user->id }}" {{ request('author') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ ucfirst($user->role) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-md-{{ auth()->user()->isAdmin() ? '5' : '7' }}">
                    <div class="position-relative">
                        <input type="text" name="search" class="form-control search-input" placeholder="{{ __('admin.press_enter_to_search') }}" value="{{ request('search') }}">
                        <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                    </div>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('admin.posts.index') }}" class="btn-minimal">{{ __('admin.clear') }}</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Posts Table -->
<div class="card-minimal">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-minimal">
                <thead>
                    <tr>
                        <th>{{ __('admin.title') }}</th>
                        <th>{{ __('admin.author') }}</th>
                        <th>{{ __('admin.status') }}</th>
                        <th>{{ __('admin.created_at') }}</th>
                        <th>{{ __('admin.published') }}</th>
                        <th>{{ __('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($post->image)
                                        <img src="{{ $post->image_url }}" alt="{{ $post->title }}"
                                             class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                    @endif
                                    <div>
                                        @php
                                            // For posts, check if current locale matches any translation
                                            // If not, fallback to main post data
                                            $translation = $post->translation($currentLocale);
                                            if ($translation) {
                                                $localizedTitle = $translation->title;
                                                $localizedContent = $translation->content;
                                                $hasTranslation = true;
                                            } else {
                                                // Fallback to main post data
                                                $localizedTitle = $post->title;
                                                $localizedContent = $post->content;
                                                $hasTranslation = false;
                                            }
                                        @endphp
                                        
                                        <h6 class="mb-0">
                                            {{ Str::limit($localizedTitle, 50) }}
                                            @if(!$hasTranslation)
                                                @if($currentLocale === 'vi')
                                                    <small class="text-warning ms-1" title="Bài viết này chưa có định dạng tiếng Việt">
                                                        <i class="bi bi-exclamation-triangle"></i>
                                                    </small>
                                                @else
                                                    <small class="text-warning ms-1" title="Sorry this writing have not update your language">
                                                        <i class="bi bi-exclamation-triangle"></i>
                                                    </small>
                                                @endif
                                            @endif
                                        </h6>
                                        
                                        <small class="text-muted">
                                            {{ Str::limit(strip_tags($localizedContent), 80) }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($post->creator)
                                        <img src="{{ $post->creator->avatar_url }}"
                                             alt="{{ $post->creator->name }}"
                                             class="rounded-circle me-2"
                                             style="width: 32px; height: 32px; object-fit: cover;"
                                             title="{{ $post->creator->name }}">
                                        <span>{{ $post->creator->name }}</span>
                                    @else
                                        <span class="text-muted">Unknown</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $post->status === 'approved' ? 'success' : ($post->status === 'pending' ? 'warning' : ($post->status === 'rejected' ? 'danger' : 'secondary')) }}">
                                    {{ __('admin.' . $post->status) }}
                                </span>
                                @if($post->status === 'rejected' && $post->rejection_reason)
                                    <br><small class="text-muted" title="{{ __('admin.rejection_reason') }}: {{ $post->rejection_reason }}">
                                        <i class="bi bi-exclamation-triangle"></i> {{ Str::limit($post->rejection_reason, 30) }}
                                    </small>
                                @endif
                            </td>
                            <td>{{ $post->created_at->locale(app()->getLocale())->translatedFormat('M d, Y') }}</td>
                            <td>{{ $post->published_at ? $post->published_at->locale(app()->getLocale())->translatedFormat('M d, Y') : '-' }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.posts.show', $post) }}" class="btn-minimal btn-primary" title="{{ __('admin.view') }}">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @php
                                        $canEdit = auth()->user()?->isAdmin() || $post->created_by === auth()->id();
                                        $canDelete = auth()->user()?->isAdmin() || ($post->created_by === auth()->id() && $post->status !== 'approved');
                                    @endphp

                                    @if($canEdit)
                                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn-minimal" title="{{ __('admin.edit') }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif

                                    @if($post->status === 'pending' && (auth()->user()?->isAdmin() || \App\Services\SettingsService::canModeratorApprovePosts()))
                                        <form action="{{ route('admin.posts.approve', $post) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-minimal btn-success" title="{{ __('admin.approve') }}">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn-minimal btn-warning" onclick="openRejectModal('{{ route('admin.posts.reject', $post) }}')" title="{{ __('admin.reject') }}">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    @endif

                                    @if($canDelete)
                                        <button type="button" class="btn-minimal btn-danger" onclick="openDeleteModal('{{ route('admin.posts.destroy', $post) }}')" title="{{ __('admin.delete') }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No posts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Custom Pagination --}}
        @if ($posts->hasPages())
            <div class="d-flex justify-content-center mt-4">
                <nav>
                    <ul class="pagination pagination-sm">
                        {{-- Previous --}}
                        @if ($posts->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">Previous</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $posts->previousPageUrl() }}">Previous</a>
                            </li>
                        @endif

                        {{-- Page Numbers --}}
                        @for ($i = 1; $i <= $posts->lastPage(); $i++)
                            @if ($i == $posts->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $i }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $posts->url($i) }}">{{ $i }}</a>
                                </li>
                            @endif
                        @endfor

                        {{-- Next --}}
                        @if ($posts->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $posts->nextPageUrl() }}">Next</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">Next</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        @endif
    </div>
    </div>

    @include('admin.components.reject-modal')
    @include('admin.components.delete-modal')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-filter functionality
    const autoFilterElements = document.querySelectorAll('.auto-filter');
    
    autoFilterElements.forEach(function(element) {
        element.addEventListener('change', function() {
            window.showLoading();
            document.getElementById('filterForm').submit();
        });
    });
});
</script>
@endpush
@endsection
