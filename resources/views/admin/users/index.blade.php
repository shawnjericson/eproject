@extends('layouts.admin')

@section('title', __('admin.users_management'))

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ __('admin.users_management') }}</h1>
        <p class="text-muted mb-0">Manage system users and permissions</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn-minimal btn-primary">
        <i class="bi bi-plus"></i> {{ __('admin.add_new') }} User
    </a>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card-minimal text-center">
            <div class="card-body">
                <h5 class="card-title">{{ $stats['total'] ?? 0 }}</h5>
                <p class="card-text text-muted">Total Users</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-minimal text-center">
            <div class="card-body">
                <h5 class="card-title">{{ $stats['admins'] ?? 0 }}</h5>
                <p class="card-text text-muted">Admins</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-minimal text-center">
            <div class="card-body">
                <h5 class="card-title">{{ $stats['moderators'] ?? 0 }}</h5>
                <p class="card-text text-muted">Moderators</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-minimal text-center">
            <div class="card-body">
                <h5 class="card-title">{{ $stats['active'] ?? 0 }}</h5>
                <p class="card-text text-muted">{{ __('admin.active') }} Users</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card-minimal mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="moderator" {{ request('role') === 'moderator' ? 'selected' : '' }}>Moderator</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('admin.active') }}</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn-minimal btn-primary me-2">{{ __('admin.filter') }}</button>
                    <a href="{{ route('admin.users.index') }}" class="btn-minimal">{{ __('admin.clear') }}</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card-minimal">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-minimal">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>{{ __('admin.role') }}</th>
                        <th>{{ __('admin.status') }}</th>
                        <th>Content</th>
                        <th>Joined</th>
                        <th>{{ __('admin.actions') }}</th>
                        </tr>
                    </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                         style="width: 40px; height: 40px;">
                                        <i class="bi bi-person-fill text-white"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                        <small class="text-muted">{{ $user->email }}</small>
                                        @if($user->id === auth()->id())
                                            <span class="badge bg-info ms-1">You</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'moderator' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $user->posts->count() }} posts<br>
                                    {{ $user->monuments->count() }} monuments
                                </small>
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn-minimal btn-primary">{{ __('admin.view') }}</a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn-minimal">{{ __('admin.edit') }}</a>

                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this user? This will also delete all their content.')">
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
                            <td colspan="6" class="text-center">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $users->appends(request()->query())->links() }}
    </div>
</div>
@endsection
