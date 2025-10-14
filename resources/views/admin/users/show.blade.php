@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ $user->name }}</h1>
        <p class="text-muted mb-0">{{ __('admin.user_details') }} - {{ $user->email }}</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn-minimal btn-primary">
                <i class="bi bi-pencil me-1"></i>{{ __('admin.edit') }}
            </a>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn-minimal btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>{{ __('admin.back') }}
        </a>
    </div>
</div>

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- User Posts -->
        @if($user->posts->count() > 0)
            <div class="card-minimal mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-file-text me-2"></i>{{ __('admin.posts') }} ({{ $user->posts->count() }})
                    </h5>
                    <a href="{{ route('admin.posts.index') }}?search={{ urlencode($user->email) }}" 
                       class="btn-minimal btn-sm btn-outline-primary">
                        <i class="bi bi-eye me-1"></i>{{ __('admin.view') }} {{ __('admin.all') }}
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">{{ __('admin.title') }}</th>
                                    <th class="border-0">{{ __('admin.status') }}</th>
                                    <th class="border-0">{{ __('admin.created_at') }}</th>
                                    <th class="border-0 text-center">{{ __('admin.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->posts->take(10) as $post)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    @if($post->image)
                                                        <img src="{{ $post->image }}" alt="{{ $post->title }}" 
                                                             class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px;">
                                                            <i class="bi bi-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">{{ Str::limit($post->title, 40) }}</h6>
                                                    <small class="text-muted">{{ Str::limit(strip_tags($post->content), 60) }}</small>
                                                </div>
                                            </div>
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
                                        <td>
                                            <small class="text-muted">{{ $post->created_at->translatedFormat('d/m/Y') }}</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.posts.show', $post) }}" 
                                                   class="btn-minimal btn-sm btn-outline-info" title="{{ __('admin.view') }}">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.posts.edit', $post) }}" 
                                                   class="btn-minimal btn-sm btn-outline-primary" title="{{ __('admin.edit') }}">
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
                        <div class="text-center p-3 border-top">
                            <a href="{{ route('admin.posts.index') }}?search={{ urlencode($user->email) }}" 
                               class="btn-minimal btn-outline-primary">
                                <i class="bi bi-arrow-right me-1"></i>{{ __('admin.view') }} {{ __('admin.all') }} {{ $user->posts->count() }} {{ __('admin.posts') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- User Monuments -->
        @if($user->monuments->count() > 0)
            <div class="card-minimal mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-building me-2"></i>{{ __('admin.monuments') }} ({{ $user->monuments->count() }})
                    </h5>
                    <a href="{{ route('admin.monuments.index') }}?search={{ urlencode($user->email) }}" 
                       class="btn-minimal btn-sm btn-outline-primary">
                        <i class="bi bi-eye me-1"></i>{{ __('admin.view') }} {{ __('admin.all') }}
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">{{ __('admin.title') }}</th>
                                    <th class="border-0">{{ __('admin.location') }}</th>
                                    <th class="border-0">{{ __('admin.status') }}</th>
                                    <th class="border-0">{{ __('admin.created_at') }}</th>
                                    <th class="border-0 text-center">{{ __('admin.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->monuments->take(10) as $monument)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    @if($monument->image)
                                                        <img src="{{ $monument->image }}" alt="{{ $monument->title }}" 
                                                             class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px;">
                                                            <i class="bi bi-building text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">{{ Str::limit($monument->title, 30) }}</h6>
                                                    <small class="text-muted">{{ Str::limit($monument->description, 50) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($monument->location)
                                                <span class="badge bg-info">{{ Str::limit($monument->location, 15) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
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
                                        <td>
                                            <small class="text-muted">{{ $monument->created_at->translatedFormat('d/m/Y') }}</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.monuments.show', $monument) }}" 
                                                   class="btn-minimal btn-sm btn-outline-info" title="{{ __('admin.view') }}">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.monuments.edit', $monument) }}" 
                                                   class="btn-minimal btn-sm btn-outline-primary" title="{{ __('admin.edit') }}">
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
                        <div class="text-center p-3 border-top">
                            <a href="{{ route('admin.monuments.index') }}?search={{ urlencode($user->email) }}" 
                               class="btn-minimal btn-outline-primary">
                                <i class="bi bi-arrow-right me-1"></i>{{ __('admin.view') }} {{ __('admin.all') }} {{ $user->monuments->count() }} {{ __('admin.monuments') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Empty State -->
        @if($user->posts->count() == 0 && $user->monuments->count() == 0)
            <div class="card-minimal">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-file-text display-1 text-muted"></i>
                    </div>
                    <h4 class="text-muted mb-3">{{ __('admin.no_content_created') }}</h4>
                    <p class="text-muted mb-4">{{ __('admin.no_content_description') }}</p>
                    <a href="{{ route('admin.posts.create') }}" class="btn-minimal btn-primary me-2">
                        <i class="bi bi-plus-circle me-1"></i>{{ __('admin.create') }} {{ __('admin.posts') }}
                    </a>
                    <a href="{{ route('admin.monuments.create') }}" class="btn-minimal btn-outline-primary">
                        <i class="bi bi-plus-circle me-1"></i>{{ __('admin.create') }} {{ __('admin.monument') }}
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- User Profile Card -->
        <div class="card-minimal mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person-circle me-2"></i>{{ __('admin.user_profile') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                         style="width: 80px; height: 80px;">
                        @if($user->avatar)
                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" 
                                 class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <i class="bi bi-person-fill text-white" style="font-size: 2.5rem;"></i>
                        @endif
                    </div>
                    <h5 class="mt-3 mb-1">{{ $user->name }}</h5>
                    @if($user->id === auth()->id())
                        <span class="badge bg-info">{{ __('admin.you') }}</span>
                    @endif
                </div>
                
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <strong>{{ __('admin.user_email') }}:</strong> 
                        <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                            {{ $user->email }}
                        </a>
                    </li>
                    <li class="mb-2">
                        <strong>{{ __('admin.user_role') }}:</strong> 
                        @if($user->role == 'admin')
                            <span class="badge bg-danger">{{ __('admin.roles.admin') }}</span>
                        @elseif($user->role == 'moderator')
                            <span class="badge bg-warning">{{ __('admin.roles.moderator') }}</span>
                        @else
                            <span class="badge bg-secondary">{{ __('admin.roles.user') }}</span>
                        @endif
                    </li>
                    <li class="mb-2">
                        <strong>{{ __('admin.user_status') }}:</strong> 
                        @if($user->status == 'active')
                            <span class="badge bg-success">{{ __('admin.active') }}</span>
                        @else
                            <span class="badge bg-secondary">{{ __('admin.inactive') }}</span>
                        @endif
                    </li>
                    <li class="mb-2">
                        <strong>{{ __('admin.joined') }}:</strong> {{ $user->created_at->translatedFormat('d/m/Y') }}
                    </li>
                    <li class="mb-2">
                        <strong>{{ __('admin.last_updated') }}:</strong> {{ $user->updated_at->translatedFormat('d/m/Y H:i') }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="card-minimal mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>{{ __('admin.statistics') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <h4 class="text-primary">{{ $user->posts->count() }}</h4>
                        <small class="text-muted">{{ __('admin.posts') }}</small>
                    </div>
                    <div class="col-6 mb-3">
                        <h4 class="text-info">{{ $user->monuments->count() }}</h4>
                        <small class="text-muted">{{ __('admin.monuments') }}</small>
                    </div>
                </div>
                
                <hr class="my-3">
                
                <div class="row text-center">
                    <div class="col-6">
                        <h5 class="text-success">{{ $user->posts->where('status', 'approved')->count() }}</h5>
                        <small class="text-muted">{{ __('admin.approved') }} {{ __('admin.posts') }}</small>
                    </div>
                    <div class="col-6">
                        <h5 class="text-success">{{ $user->monuments->where('status', 'approved')->count() }}</h5>
                        <small class="text-muted">{{ __('admin.approved') }} {{ __('admin.monuments') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="card-minimal">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-lightning me-2"></i>{{ __('admin.quick_actions') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn-minimal btn-primary">
                        <i class="bi bi-pencil me-2"></i>{{ __('admin.edit') }} {{ __('admin.user') }}
                    </a>
                    
                    <a href="mailto:{{ $user->email }}" class="btn-minimal btn-outline-primary">
                        <i class="bi bi-envelope me-2"></i>{{ __('admin.send_email') }}
                    </a>
                    
                    @if($user->posts->count() > 0)
                        <a href="{{ route('admin.posts.index') }}?search={{ urlencode($user->email) }}" 
                           class="btn-minimal btn-outline-info">
                            <i class="bi bi-file-text me-2"></i>{{ __('admin.view') }} {{ __('admin.all') }} {{ __('admin.posts') }}
                        </a>
                    @endif
                    
                    @if($user->monuments->count() > 0)
                        <a href="{{ route('admin.monuments.index') }}?search={{ urlencode($user->email) }}" 
                           class="btn-minimal btn-outline-info">
                            <i class="bi bi-building me-2"></i>{{ __('admin.view') }} {{ __('admin.all') }} {{ __('admin.monuments') }}
                        </a>
                    @endif
                    
                    @if($user->id !== auth()->id())
                        <hr class="my-3">
                        <form action="{{ route('admin.users.destroy', $user) }}" 
                              method="POST" 
                              onsubmit="return confirm('{{ __('admin.confirm_delete_user') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-minimal btn-danger w-100">
                                <i class="bi bi-trash me-2"></i>{{ __('admin.delete') }} {{ __('admin.user') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection