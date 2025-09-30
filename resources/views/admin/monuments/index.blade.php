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
        <form method="GET" action="{{ route('admin.monuments.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>{{ __('admin.draft') }}</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('admin.pending') }}</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ __('admin.approved') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="zone" class="form-select">
                        <option value="">All Zones</option>
                        <option value="East" {{ request('zone') === 'East' ? 'selected' : '' }}>East</option>
                        <option value="North" {{ request('zone') === 'North' ? 'selected' : '' }}>North</option>
                        <option value="West" {{ request('zone') === 'West' ? 'selected' : '' }}>West</option>
                        <option value="South" {{ request('zone') === 'South' ? 'selected' : '' }}>South</option>
                        <option value="Central" {{ request('zone') === 'Central' ? 'selected' : '' }}>Central</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search monuments..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn-minimal btn-primary me-2">{{ __('admin.filter') }}</button>
                    <a href="{{ route('admin.monuments.index') }}" class="btn-minimal">{{ __('admin.clear') }}</a>
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
                        <th>{{ __('admin.title') }}</th>
                        <th>Zone</th>
                        <th>{{ __('admin.status') }}</th>
                        <th>{{ __('admin.author') }}</th>
                        <th>{{ __('admin.created_at') }}</th>
                        <th>{{ __('admin.actions') }}</th>
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
                                        <h6 class="mb-0">{{ Str::limit($monument->title, 50) }}</h6>
                                        <small class="text-muted">{{ Str::limit($monument->description, 80) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $monument->zone }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $monument->status === 'approved' ? 'success' : ($monument->status === 'pending' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($monument->status) }}
                                </span>
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
                                <small class="text-muted">{{ $monument->created_at->format('M d, Y') }}</small>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.monuments.show', $monument) }}" class="btn-minimal btn-primary">{{ __('admin.view') }}</a>

                                    @php
                                        $canEdit = auth()->user()?->isAdmin() || $monument->created_by === auth()->id();
                                        $canDelete = auth()->user()?->isAdmin() || ($monument->created_by === auth()->id() && $monument->status !== 'approved');
                                    @endphp

                                    @if($canEdit)
                                        <a href="{{ route('admin.monuments.edit', $monument) }}" class="btn-minimal">{{ __('admin.edit') }}</a>
                                    @endif

                                    @if($monument->status === 'pending' && auth()->user()?->isAdmin())
                                        <form action="{{ route('admin.monuments.approve', $monument) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-minimal btn-success">Approve</button>
                                        </form>
                                    @endif

                                    @if($canDelete)
                                        <form action="{{ route('admin.monuments.destroy', $monument) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this monument?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-minimal btn-danger">{{ __('admin.delete') }}</button>
                                        </form>
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

        {{ $monuments->appends(request()->query())->links() }}
    </div>
</div>
@endsection
