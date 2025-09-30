@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ $user->name }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> {{ __('admin.edit') }}
            </a>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> {{ __('admin.back') }} to Users
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- User Posts -->
        @if($user->posts->count() > 0)
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Posts ({{ $user->posts->count() }})</h5>
                    <a href="{{ route('admin.posts.index') }}?search={{ urlencode($user->email) }}" 
                       class="btn btn-sm btn-outline-primary">
                        {{ __('admin.view') }} All Posts
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('admin.title') }}</th>
                                    <th>{{ __('admin.status') }}</th>
                                    <th>{{ __('admin.created_at') }}</th>
                                    <th>{{ __('admin.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->posts->take(10) as $post)
                                    <tr>
                                        <td>
                                            <strong>{{ Str::limit($post->title, 40) }}</strong>
                                        </td>
                                        <td>
                                            @if($post->status == 'approved')
                                                <span class="badge bg-success">{{ __('admin.approved') }}</span>
                                            @elseif($post->status == 'pending')
                                                <span class="badge bg-warning">{{ __('admin.pending') }}</span>
                                            @elseif($post->status == 'rejected')
                                                <span class="badge bg-danger">{{ __('admin.rejected') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ __('admin.draft') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $post->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.posts.show', $post) }}" 
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.posts.edit', $post) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($user->posts->count() > 10)
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.posts.index') }}?search={{ urlencode($user->email) }}" 
                               class="btn btn-outline-primary">
                                {{ __('admin.view') }} All {{ $user->posts->count() }} Posts
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- User Monuments -->
        @if($user->monuments->count() > 0)
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Monuments ({{ $user->monuments->count() }})</h5>
                    <a href="{{ route('admin.monuments.index') }}?search={{ urlencode($user->email) }}" 
                       class="btn btn-sm btn-outline-primary">
                        {{ __('admin.view') }} All Monuments
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('admin.title') }}</th>
                                    <th>Zone</th>
                                    <th>{{ __('admin.status') }}</th>
                                    <th>{{ __('admin.created_at') }}</th>
                                    <th>{{ __('admin.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->monuments->take(10) as $monument)
                                    <tr>
                                        <td>
                                            <strong>{{ Str::limit($monument->title, 30) }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $monument->zone }}</span>
                                        </td>
                                        <td>
                                            @if($monument->status == 'approved')
                                                <span class="badge bg-success">{{ __('admin.approved') }}</span>
                                            @elseif($monument->status == 'pending')
                                                <span class="badge bg-warning">{{ __('admin.pending') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ __('admin.draft') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $monument->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.monuments.show', $monument) }}" 
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.monuments.edit', $monument) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($user->monuments->count() > 10)
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.monuments.index') }}?search={{ urlencode($user->email) }}" 
                               class="btn btn-outline-primary">
                                {{ __('admin.view') }} All {{ $user->monuments->count() }} Monuments
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Empty State -->
        @if($user->posts->count() == 0 && $user->monuments->count() == 0)
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-file-text display-1 text-muted"></i>
                    <h4 class="mt-3">No content {{ __('admin.created_at') }}</h4>
                    <p class="text-muted">This user hasn't created any posts or monuments yet.</p>
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-4">
        <!-- User Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">User Information</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-person-fill text-white" style="font-size: 2rem;"></i>
                    </div>
                </div>
                
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <strong>{{ __('admin.name') }}:</strong> {{ $user->name }}
                        @if($user->id === auth()->id())
                            <span class="badge bg-info ms-1">You</span>
                        @endif
                    </li>
                    <li class="mb-2">
                        <strong>{{ __('admin.email') }}:</strong> 
                        <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                            {{ $user->email }}
                        </a>
                    </li>
                    <li class="mb-2">
                        <strong>{{ __('admin.role') }}:</strong> 
                        @if($user->role == 'admin')
                            <span class="badge bg-danger">Admin</span>
                        @elseif($user->role == 'moderator')
                            <span class="badge bg-warning">Moderator</span>
                        @else
                            <span class="badge bg-secondary">User</span>
                        @endif
                    </li>
                    <li class="mb-2">
                        <strong>{{ __('admin.status') }}:</strong> 
                        @if($user->status == 'active')
                            <span class="badge bg-success">{{ __('admin.active') }}</span>
                        @else
                            <span class="badge bg-secondary">{{ __('admin.inactive') }}</span>
                        @endif
                    </li>
                    <li class="mb-2">
                        <strong>Joined:</strong> {{ $user->created_at->format('M d, Y') }}
                    </li>
                    <li class="mb-2">
                        <strong>Last updated:</strong> {{ $user->updated_at->diffForHumans() }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Statistics -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary">{{ $user->posts->count() }}</h4>
                        <small class="text-muted">Posts</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-info">{{ $user->monuments->count() }}</h4>
                        <small class="text-muted">Monuments</small>
                    </div>
                </div>
                
                <hr>
                
                <div class="row text-center">
                    <div class="col-6">
                        <h5 class="text-success">{{ $user->posts->where('status', 'approved')->count() }}</h5>
                        <small class="text-muted">{{ __('admin.approved') }} Posts</small>
                    </div>
                    <div class="col-6">
                        <h5 class="text-success">{{ $user->monuments->where('status', 'approved')->count() }}</h5>
                        <small class="text-muted">{{ __('admin.approved') }} Monuments</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick {{ __('admin.actions') }} -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick {{ __('admin.actions') }}</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> {{ __('admin.edit') }} User
                    </a>
                    
                    <a href="mailto:{{ $user->email }}" class="btn btn-outline-primary">
                        <i class="bi bi-envelope"></i> Send {{ __('admin.email') }}
                    </a>
                    
                    @if($user->posts->count() > 0)
                        <a href="{{ route('admin.posts.index') }}?search={{ urlencode($user->email) }}" 
                           class="btn btn-outline-info">
                            <i class="bi bi-file-text"></i> {{ __('admin.view') }} All Posts
                        </a>
                    @endif
                    
                    @if($user->monuments->count() > 0)
                        <a href="{{ route('admin.monuments.index') }}?search={{ urlencode($user->email) }}" 
                           class="btn btn-outline-info">
                            <i class="bi bi-building"></i> {{ __('admin.view') }} All Monuments
                        </a>
                    @endif
                    
                    @if($user->id !== auth()->id())
                        <hr>
                        <form action="{{ route('admin.users.destroy', $user) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this user? This will also delete all their content.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-trash"></i> {{ __('admin.delete') }} User
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
