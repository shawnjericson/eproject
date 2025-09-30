@extends('layouts.admin')

@section('title', __('admin.posts_management'))

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ __('admin.posts_management') }}</h1>
        <p class="text-muted mb-0">Manage all posts and articles</p>
    </div>
    <a href="{{ route('admin.posts.create') }}" class="btn-minimal btn-primary">
        {{ __('admin.create_new_post') }}
    </a>
</div>

<!-- Filters -->
<div class="card-minimal mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.posts.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>{{ __('admin.draft') }}</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('admin.pending') }}</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ __('admin.approved') }}</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ __('admin.rejected') }}</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Search posts..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn-minimal btn-primary me-2">{{ __('admin.filter') }}</button>
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
                                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" 
                                             class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ Str::limit($post->title, 50) }}</h6>
                                        <small class="text-muted">{{ Str::limit(strip_tags($post->content), 80) }}</small>
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
                                    {{ ucfirst($post->status) }}
                                </span>
                            </td>
                            <td>{{ $post->created_at->format('M d, Y') }}</td>
                            <td>{{ $post->published_at ? $post->published_at->format('M d, Y') : '-' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.posts.show', $post) }}" class="btn-minimal btn-primary">{{ __('admin.view') }}</a>

                                    @php
                                        $canEdit = auth()->user()?->isAdmin() || $post->created_by === auth()->id();
                                        $canDelete = auth()->user()?->isAdmin() || ($post->created_by === auth()->id() && $post->status !== 'approved');
                                    @endphp

                                    @if($canEdit)
                                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn-minimal">{{ __('admin.edit') }}</a>
                                    @endif

                                    @if($post->status === 'pending' && auth()->user()?->isAdmin())
                                        <form action="{{ route('admin.posts.approve', $post) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-minimal btn-success">Approve</button>
                                        </form>
                                        <form action="{{ route('admin.posts.reject', $post) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-minimal btn-warning">Reject</button>
                                        </form>
                                    @endif

                                    @if($canDelete)
                                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this post?')">
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
                            <td colspan="6" class="text-center">No posts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $posts->links() }}
    </div>
</div>
@endsection
