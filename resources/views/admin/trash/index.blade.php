@extends('layouts.admin')

@section('title', 'Trash Management')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-2">
                <i class="bi bi-trash text-danger me-2"></i>Trash Management
            </h1>
            <p class="text-muted mb-0">Manage deleted posts and monuments</p>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.trash.empty') }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Are you sure you want to permanently delete ALL items in trash? This action cannot be undone!')">
                @csrf
                <button type="submit" class="btn btn-modern-danger">
                    <i class="bi bi-trash3 me-2"></i>Empty Trash
                </button>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <!-- Deleted Posts -->
    <div class="col-lg-6 mb-4">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-file-text text-primary me-2"></i>Deleted Posts
                    <span class="badge bg-secondary ms-2">{{ $deletedPosts->total() }}</span>
                </h5>
            </div>
            <div class="card-body">
                @if($deletedPosts->count() > 0)
                    @foreach($deletedPosts as $post)
                        <div class="trash-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="trash-content">
                                    <h6 class="trash-title mb-1">{{ $post->title }}</h6>
                                    <p class="text-muted small mb-2">{{ Str::limit($post->content, 100) }}</p>
                                    <div class="trash-meta">
                                        <small class="text-muted">
                                            <i class="bi bi-person me-1"></i>{{ $post->creator->name ?? 'Unknown' }}
                                            <i class="bi bi-clock me-1 ms-2"></i>Deleted {{ $post->deleted_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                                <div class="trash-actions">
                                    <form action="{{ route('admin.trash.restore-post', $post->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-modern-success" title="Restore">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.trash.force-delete-post', $post->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to permanently delete this post? This action cannot be undone!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-modern-danger" title="Permanently Delete">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    {{-- Custom Pagination --}}
                    @if ($deletedPosts->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            <nav>
                                <ul class="pagination pagination-sm">
                                    {{-- Previous --}}
                                    @if ($deletedPosts->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">{{ __('admin.previous') }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $deletedPosts->appends(request()->query())->previousPageUrl() }}">{{ __('admin.previous') }}</a>
                                        </li>
                                    @endif

                                    {{-- Page Numbers --}}
                                    @for ($i = 1; $i <= $deletedPosts->lastPage(); $i++)
                                        @if ($i == $deletedPosts->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $i }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $deletedPosts->appends(request()->query())->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor

                                    {{-- Next --}}
                                    @if ($deletedPosts->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $deletedPosts->appends(request()->query())->nextPageUrl() }}">{{ __('admin.next') }}</a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">{{ __('admin.next') }}</span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-trash display-4 text-muted"></i>
                        <p class="text-muted mt-2">No deleted posts found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Deleted Monuments -->
    <div class="col-lg-6 mb-4">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-building text-primary me-2"></i>Deleted Monuments
                    <span class="badge bg-secondary ms-2">{{ $deletedMonuments->total() }}</span>
                </h5>
            </div>
            <div class="card-body">
                @if($deletedMonuments->count() > 0)
                    @foreach($deletedMonuments as $monument)
                        <div class="trash-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="trash-content">
                                    <h6 class="trash-title mb-1">{{ $monument->title }}</h6>
                                    <p class="text-muted small mb-2">{{ Str::limit($monument->description, 100) }}</p>
                                    <div class="trash-meta">
                                        <small class="text-muted">
                                            <i class="bi bi-person me-1"></i>{{ $monument->creator->name ?? 'Unknown' }}
                                            <i class="bi bi-clock me-1 ms-2"></i>Deleted {{ $monument->deleted_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                                <div class="trash-actions">
                                    <form action="{{ route('admin.trash.restore-monument', $monument->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-modern-success" title="Restore">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.trash.force-delete-monument', $monument->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to permanently delete this monument? This action cannot be undone!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-modern-danger" title="Permanently Delete">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    {{-- Custom Pagination --}}
                    @if ($deletedMonuments->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            <nav>
                                <ul class="pagination pagination-sm">
                                    {{-- Previous --}}
                                    @if ($deletedMonuments->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">{{ __('admin.previous') }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $deletedMonuments->appends(request()->query())->previousPageUrl() }}">{{ __('admin.previous') }}</a>
                                        </li>
                                    @endif

                                    {{-- Page Numbers --}}
                                    @for ($i = 1; $i <= $deletedMonuments->lastPage(); $i++)
                                        @if ($i == $deletedMonuments->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $i }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $deletedMonuments->appends(request()->query())->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor

                                    {{-- Next --}}
                                    @if ($deletedMonuments->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $deletedMonuments->appends(request()->query())->nextPageUrl() }}">{{ __('admin.next') }}</a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">{{ __('admin.next') }}</span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-trash display-4 text-muted"></i>
                        <p class="text-muted mt-2">No deleted monuments found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.trash-item {
    padding: 1rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    margin-bottom: 1rem;
    background: #f8f9fa;
    transition: all 0.2s ease;
}

.trash-item:hover {
    background: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.trash-content {
    flex: 1;
    min-width: 0; /* Allow flex item to shrink below content size */
    margin-right: 1rem;
}

.trash-title {
    word-wrap: break-word;
    word-break: break-word;
    overflow-wrap: break-word;
    hyphens: auto;
    max-width: 100%;
    line-height: 1.4;
}

.trash-actions {
    flex-shrink: 0; /* Prevent actions from shrinking */
    display: flex;
    gap: 0.5rem;
    align-items: flex-start;
}

.trash-meta {
    margin-top: 0.5rem;
}

/* Ensure text doesn't overflow */
.trash-item .text-muted {
    word-wrap: break-word;
    word-break: break-word;
    overflow-wrap: break-word;
}
</style>
@endpush


