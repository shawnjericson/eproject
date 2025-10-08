@extends('layouts.admin')

@section('title', __('admin.gallery_management'))

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ __('admin.gallery_management') }}</h1>
        <p class="text-muted mb-0">{{ __('admin.manage_gallery_images_media') }}</p>
    </div>
    <a href="{{ route('admin.gallery.create') }}" class="btn-minimal btn-primary">
        <i class="bi bi-plus"></i> {{ __('admin.add_new') }} {{ __('admin.image') }}
    </a>
</div>

<!-- Filters -->
<div class="card-minimal mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.gallery.index') }}" id="filterForm">
            <div class="row g-3">
                <div class="col-md-5">
                    <select name="monument_id" class="form-select auto-filter">
                        <option value="">All Monuments</option>
                        @foreach($monuments as $monument)
                            <option value="{{ $monument->id }}" {{ request('monument_id') == $monument->id ? 'selected' : '' }}>
                                {{ $monument->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Search by title or description..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.gallery.index') }}" class="btn-minimal">{{ __('admin.clear') }}</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Gallery Grid -->
<div class="card-minimal">
    <div class="card-body">
        <div class="row">
            @forelse($galleries as $gallery)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card-minimal h-100">
                            <div class="position-relative">
                                <img src="{{ $gallery->image_url }}"
                                     alt="{{ $gallery->title }}"
                                     class="card-img-top" style="height: 200px; object-fit: cover;">
                                <div class="position-absolute top-0 end-0 p-2">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light btn-outline-secondary dropdown-toggle" 
                                                type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @php
                                                $canEdit = auth()->user()?->isAdmin() || $gallery->monument->created_by === auth()->id();
                                            @endphp

                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.gallery.show', $gallery) }}">
                                                    <i class="bi bi-eye"></i> {{ __('admin.view') }}
                                                </a>
                                            </li>
                                            @if($canEdit)
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.gallery.edit', $gallery) }}">
                                                        <i class="bi bi-pencil"></i> {{ __('admin.edit') }}
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('admin.gallery.destroy', $gallery) }}"
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this image?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="bi bi-trash"></i> {{ __('admin.delete') }}
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title">{{ $gallery->title }}</h6>
                                <p class="card-text small text-muted mb-2">
                                    <i class="bi bi-building"></i> {{ $gallery->monument->title }}
                                </p>
                                @if($gallery->description)
                                    <p class="card-text small">{{ Str::limit($gallery->description, 80) }}</p>
                                @endif
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center">
                                        @if($gallery->monument->creator)
                                            <img src="{{ $gallery->monument->creator->avatar_url }}"
                                                 alt="{{ $gallery->monument->creator->name }}"
                                                 class="rounded-circle me-2"
                                                 style="width: 24px; height: 24px; object-fit: cover;"
                                                 title="{{ $gallery->monument->creator->name }}">
                                            <small class="text-muted">{{ $gallery->monument->creator->name }}</small>
                                        @else
                                            <small class="text-muted">Unknown</small>
                                        @endif
                                    </div>
                                    <small class="text-muted">{{ $gallery->created_at->format('M d, Y') }}</small>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div></div>
                                    <div class="d-flex gap-1">
                                        @php
                                            $canEdit = auth()->user()?->isAdmin() || $gallery->monument->created_by === auth()->id();
                                        @endphp

                                        <a href="{{ route('admin.gallery.show', $gallery) }}" class="btn-minimal btn-primary btn-sm">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($canEdit)
                                            <a href="{{ route('admin.gallery.edit', $gallery) }}" class="btn-minimal btn-sm">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.gallery.destroy', $gallery) }}"
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Delete this image?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-minimal btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="bi bi-images display-1 text-muted"></i>
                        <h4 class="mt-3">{{ __('admin.no_gallery_images_found') }}</h4>
                        <p class="text-muted">Start by adding some images to your monuments.</p>
                        <a href="{{ route('admin.gallery.create') }}" class="btn-minimal btn-primary">
                            <i class="bi bi-plus"></i> {{ __('admin.add_new') }} {{ __('admin.image') }}
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Custom Pagination --}}
        @if ($galleries->hasPages())
            <div class="d-flex justify-content-center mt-4">
                <nav>
                    <ul class="pagination pagination-sm">
                        {{-- Previous --}}
                        @if ($galleries->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">Previous</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $galleries->appends(request()->query())->previousPageUrl() }}">Previous</a>
                            </li>
                        @endif

                        {{-- Page Numbers --}}
                        @for ($i = 1; $i <= $galleries->lastPage(); $i++)
                            @if ($i == $galleries->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $i }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $galleries->appends(request()->query())->url($i) }}">{{ $i }}</a>
                                </li>
                            @endif
                        @endfor

                        {{-- Next --}}
                        @if ($galleries->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $galleries->appends(request()->query())->nextPageUrl() }}">Next</a>
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
@endsection

@push('styles')
<style>
.card-img-top {
    transition: transform 0.3s ease;
}
.card:hover .card-img-top {
    transform: scale(1.05);
}
.card {
    transition: box-shadow 0.3s ease;
}
.card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>
@endpush
