@extends('layouts.admin')

@section('title', __('admin.monuments_management'))

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ __('admin.monuments_management') }}</h1>
        <p class="text-muted mb-0">Manage all monuments and heritage sites</p>
    </div>
    <a href="{{ route('admin.monuments.create') }}" class="btn-minimal btn-primary">
        <i class="bi bi-plus"></i> {{ __('admin.create_new_monument') }}
    </a>
</div>

<!-- Filters -->
<div class="card-minimal mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.monuments.index') }}" id="filterForm">
            <div class="row g-3">
                <div class="col-md-3">
                    <select name="status" class="form-select auto-filter">
                        <option value="">{{ __('admin.all_status') }}</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>{{ __('admin.draft') }}</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('admin.pending') }}</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ __('admin.approved') }}</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ __('admin.rejected') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="zone" class="form-select auto-filter">
                        <option value="">{{ __('admin.all_zones') }}</option>
                        <option value="East" {{ request('zone') === 'East' ? 'selected' : '' }}>{{ __('admin.zones.east') }}</option>
                        <option value="North" {{ request('zone') === 'North' ? 'selected' : '' }}>{{ __('admin.zones.north') }}</option>
                        <option value="West" {{ request('zone') === 'West' ? 'selected' : '' }}>{{ __('admin.zones.west') }}</option>
                        <option value="South" {{ request('zone') === 'South' ? 'selected' : '' }}>{{ __('admin.zones.south') }}</option>
                        <option value="Central" {{ request('zone') === 'Central' ? 'selected' : '' }}>{{ __('admin.zones.central') }}</option>
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
                <div class="col-md-{{ auth()->user()->isAdmin() ? '3' : '5' }}">
                    <div class="position-relative">
                        <input type="text" name="search" class="form-control search-input" placeholder="{{ __('admin.monument_placeholder') }}" value="{{ request('search') }}">
                        <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                    </div>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.monuments.index') }}" class="btn-minimal">{{ __('admin.reset') }}</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Monuments Table -->
<div class="card-minimal">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-minimal">
                <thead>
                    <tr>
                        <th style="width: 35%;">{{ __('admin.title') }}</th>
                        <th style="width: 12%;">{{ __('admin.monument_zone') }}</th>
                        <th style="width: 12%;">{{ __('admin.status') }}</th>
                        <th style="width: 15%;">{{ __('admin.author') }}</th>
                        <th style="width: 12%;">{{ __('admin.created_at') }}</th>
                        <th style="width: 14%;">{{ __('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monuments as $monument)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($monument->image)
                                        <img src="{{ $monument->image_url }}" alt="{{ $monument->title }}"
                                             class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                    @endif
                                    <div>
                                        @php
                                            // For Vietnamese (vi), use base monument data
                                            // For other languages, use translations
                                            if ($currentLocale === 'vi') {
                                                $localizedTitle = $monument->title;
                                                $localizedDescription = $monument->description;
                                                $hasTranslation = true; // Vietnamese is always available as base language
                                            } else {
                                                $localizedTitle = $monument->getTitle($currentLocale);
                                                $localizedDescription = $monument->getDescription($currentLocale);
                                                $hasTranslation = $monument->translation($currentLocale);
                                            }
                                        @endphp
                                        
                                        <h6 class="mb-0">
                                            {{ Str::limit($localizedTitle, 50) }}
                                            @if(!$hasTranslation && $currentLocale !== 'vi')
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
                                            {{ Str::limit($localizedDescription, 80) }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ __('admin.zones.' . strtolower($monument->zone)) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $monument->status === 'approved' ? 'success' : ($monument->status === 'pending' ? 'warning' : ($monument->status === 'rejected' ? 'danger' : 'secondary')) }}">
                                    {{ __('admin.' . $monument->status) }}
                                </span>
                                @if($monument->status === 'rejected' && $monument->rejection_reason)
                                    <br><small class="text-muted" title="Rejection reason: {{ $monument->rejection_reason }}">
                                        <i class="bi bi-exclamation-triangle"></i> {{ Str::limit($monument->rejection_reason, 30) }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($monument->creator)
                                        <img src="{{ $monument->creator->avatar_url }}"
                                             alt="{{ $monument->creator->name }}"
                                             class="rounded-circle me-2"
                                             style="width: 32px; height: 32px; object-fit: cover;"
                                             title="{{ $monument->creator->name }}">
                                        <span>{{ $monument->creator->name }}</span>
                                    @else
                                        <span class="text-muted">Unknown</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <small class="text-muted">{{ $monument->created_at->locale(app()->getLocale())->translatedFormat('M d, Y') }}</small>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.monuments.show', $monument) }}" class="btn-minimal btn-primary" title="{{ __('admin.view') }}">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @php
                                        $canEdit = auth()->user()?->isAdmin() || $monument->created_by === auth()->id();
                                        $canDelete = auth()->user()?->isAdmin() || ($monument->created_by === auth()->id() && $monument->status !== 'approved');
                                    @endphp

                                    @if($canEdit)
                                        <a href="{{ route('admin.monuments.edit', $monument) }}" class="btn-minimal" title="{{ __('admin.edit') }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif

                                    @if($monument->status === 'pending' && (auth()->user()?->isAdmin() || \App\Services\SettingsService::canModeratorApproveMonuments()))
                                        <form action="{{ route('admin.monuments.approve', $monument) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-minimal btn-success" title="{{ __('admin.approve') }}">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn-minimal btn-warning" onclick="openRejectModal('{{ route('admin.monuments.reject', $monument) }}')" title="{{ __('admin.reject') }}">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    @endif

                                    @if($canDelete)
                                        <button type="button" class="btn-minimal btn-danger" onclick="openDeleteModal('{{ route('admin.monuments.destroy', $monument) }}')" title="{{ __('admin.delete') }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No monuments found.</td>
                        </tr>
                    @endforelse
                    </tbody>
            </table>
        </div>

        {{-- Custom Pagination --}}
        @if ($monuments->hasPages())
            <div class="d-flex justify-content-center mt-4">
                <nav>
                    <ul class="pagination pagination-sm">
                        {{-- Previous --}}
                        @if ($monuments->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">{{ __('admin.previous') }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $monuments->appends(request()->query())->previousPageUrl() }}">{{ __('admin.previous') }}</a>
                            </li>
                        @endif

                        {{-- Page Numbers --}}
                        @for ($i = 1; $i <= $monuments->lastPage(); $i++)
                            @if ($i == $monuments->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $i }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $monuments->appends(request()->query())->url($i) }}">{{ $i }}</a>
                                </li>
                            @endif
                        @endfor

                        {{-- Next --}}
                        @if ($monuments->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $monuments->appends(request()->query())->nextPageUrl() }}">{{ __('admin.next') }}</a>
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
