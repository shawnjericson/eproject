@extends('layouts.admin')

@section('title', __('admin.feedbacks_management'))

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ __('admin.feedbacks_management') }}</h1>
        <p class="text-muted mb-0">{{ __('admin.manage_user_feedback') }}</p>
    </div>
    <div class="btn-group">
        <button type="button" class="btn-minimal dropdown-toggle" data-bs-toggle="dropdown">
            <i class="bi bi-funnel"></i> {{ __('admin.filter') }}
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('admin.feedbacks.index') }}">All Feedbacks</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.feedbacks.index') }}?days=7">Last 7 days</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.feedbacks.index') }}?days=30">Last 30 days</a></li>
        </ul>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card-minimal text-center">
            <div class="card-body">
                <h5 class="card-title">{{ $stats['total'] ?? 0 }}</h5>
                <p class="card-text text-muted">{{ __('admin.total_feedbacks') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-minimal text-center">
            <div class="card-body">
                <h5 class="card-title">{{ $stats['today'] ?? 0 }}</h5>
                <p class="card-text text-muted">Today</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-minimal text-center">
            <div class="card-body">
                <h5 class="card-title">{{ $stats['this_week'] ?? 0 }}</h5>
                <p class="card-text text-muted">This Week</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-minimal text-center">
            <div class="card-body">
                <h5 class="card-title">{{ $stats['this_month'] ?? 0 }}</h5>
                <p class="card-text text-muted">This Month</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card-minimal mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.feedbacks.index') }}" id="filterForm">
            <div class="row g-3">
                <div class="col-md-3">
                    <select name="monument_id" class="form-select auto-filter">
                        <option value="">All Monuments</option>
                        @foreach($monuments ?? [] as $monument)
                            <option value="{{ $monument->id }}" {{ request('monument_id') == $monument->id ? 'selected' : '' }}>
                                {{ $monument->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="days" class="form-select auto-filter">
                        <option value="">All Time</option>
                        <option value="1" {{ request('days') == '1' ? 'selected' : '' }}>Today</option>
                        <option value="7" {{ request('days') == '7' ? 'selected' : '' }}>Last 7 days</option>
                        <option value="30" {{ request('days') == '30' ? 'selected' : '' }}>Last 30 days</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by name, email, or message..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <a href="{{ route('admin.feedbacks.index') }}" class="btn-minimal">{{ __('admin.clear') }}</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Feedbacks List -->
<div class="card-minimal">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-minimal">
                <thead>
                    <tr>
                        <th>{{ __('admin.name') }}</th>
                        <th>Monument</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>{{ __('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feedbacks as $feedback)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($feedback->name) }}&size=32&background=random"
                                         alt="{{ $feedback->name }}"
                                         class="rounded-circle me-2"
                                         style="width: 32px; height: 32px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0">{{ $feedback->name }}</h6>
                                        <small class="text-muted">{{ $feedback->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($feedback->monument)
                                    <a href="{{ route('admin.monuments.show', $feedback->monument) }}"
                                       class="text-decoration-none">
                                        {{ Str::limit($feedback->monument->title, 30) }}
                                    </a>
                                    <br>
                                    <small class="text-muted">{{ $feedback->monument->zone }}</small>
                                @else
                                    <span class="text-muted">General Feedback</span>
                                @endif
                            </td>
                            <td>
                                <div class="message-preview">
                                    {{ Str::limit($feedback->message, 80) }}
                                    @if(strlen($feedback->message) > 80)
                                        <a href="{{ route('admin.feedbacks.show', $feedback) }}"
                                           class="text-primary text-decoration-none">...read more</a>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $feedback->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.feedbacks.show', $feedback) }}" class="btn-minimal btn-primary">{{ __('admin.view') }}</a>
                                    <a href="mailto:{{ $feedback->email }}?subject=Re: Your feedback about {{ $feedback->monument ? $feedback->monument->title : 'our website' }}"
                                       class="btn-minimal">Reply</a>
                                    <form action="{{ route('admin.feedbacks.destroy', $feedback) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this feedback?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-minimal btn-danger">{{ __('admin.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-chat-dots display-4 text-muted"></i>
                                <h5 class="mt-3">{{ __('admin.no_feedbacks_found') }}</h5>
                                <p class="text-muted">
                                    @if(request()->hasAny(['search', 'monument_id', 'days']))
                                        Try adjusting your filters to see more results.
                                    @else
                                        {{ __('admin.feedbacks_will_appear_here') }}
                                    @endif
                                </p>
                                @if(request()->hasAny(['search', 'monument_id', 'days']))
                                    <a href="{{ route('admin.feedbacks.index') }}" class="btn-minimal btn-primary">
                                        <i class="bi bi-arrow-clockwise"></i> Clear Filters
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Custom Pagination --}}
        @if ($feedbacks->hasPages())
            <div class="d-flex justify-content-center mt-4 mb-3">
                <nav>
                    <ul class="pagination pagination-sm">
                        {{-- Previous --}}
                        @if ($feedbacks->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">Previous</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $feedbacks->appends(request()->query())->previousPageUrl() }}">Previous</a>
                            </li>
                        @endif

                        {{-- Page Numbers --}}
                        @for ($i = 1; $i <= $feedbacks->lastPage(); $i++)
                            @if ($i == $feedbacks->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $i }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $feedbacks->appends(request()->query())->url($i) }}">{{ $i }}</a>
                                </li>
                            @endif
                        @endfor

                        {{-- Next --}}
                        @if ($feedbacks->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $feedbacks->appends(request()->query())->nextPageUrl() }}">Next</a>
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
.message-preview {
    max-width: 300px;
    word-wrap: break-word;
}
.table td {
    vertical-align: middle;
}
</style>
@endpush
