@extends('layouts.admin')

@section('title', __('admin.users_management'))

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ __('admin.users_management') }}</h1>
        <p class="text-muted mb-0">{{ __('admin.manage_system_users_permissions') }}</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn-minimal btn-primary">
        <i class="bi bi-plus"></i> {{ __('admin.add_new_user') }}
    </a>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card-minimal text-center">
            <div class="card-body">
                <h5 class="card-title">{{ $stats['total'] ?? 0 }}</h5>
                <p class="card-text text-muted">{{ __('admin.total_users') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-minimal text-center">
            <div class="card-body">
                <h5 class="card-title">{{ $stats['admins'] ?? 0 }}</h5>
                <p class="card-text text-muted">{{ __('admin.admins') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-minimal text-center">
            <div class="card-body">
                <h5 class="card-title">{{ $stats['moderators'] ?? 0 }}</h5>
                <p class="card-text text-muted">{{ __('admin.moderators') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-minimal text-center">
            <div class="card-body">
                <h5 class="card-title">{{ $stats['active'] ?? 0 }}</h5>
                <p class="card-text text-muted">{{ __('admin.active_user') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card-minimal mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users.index') }}" id="filterForm">
            <div class="row g-3">
                <div class="col-md-3">
                    <select name="role" class="form-select auto-filter">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>{{ __('admin.admins') }}</option>
                        <option value="moderator" {{ request('role') === 'moderator' ? 'selected' : '' }}>{{ __('admin.moderators') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select auto-filter">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('admin.active') }}</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="position-relative">
                        <input type="text" name="search" class="form-control search-input" placeholder="{{ __('admin.user_placeholder') }}" value="{{ request('search') }}">
                        <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                    </div>
                </div>
                <div class="col-md-2">
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
                        <th>{{ __('admin.user') }}</th>
                        <th>{{ __('admin.role') }}</th>
                        <th>{{ __('admin.status') }}</th>
                        <th>{{ __('admin.content') }}</th>
                        <th>{{ __('admin.joined') }}</th>
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
                                            <span class="badge bg-info ms-1">{{ __('admin.you') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger">{{ __('admin.roles.admin') }}</span>
                                @elseif($user->role === 'moderator')
                                    <span class="badge bg-warning">{{ __('admin.roles.moderator') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('admin.roles.user') }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ __('admin.' . $user->status) }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $user->posts->count() }} {{ __('admin.posts') }}<br>
                                    {{ $user->monuments->count() }} {{ __('admin.monuments') }}
                                </small>
                            </td>
                            <td>{{ $user->created_at->translatedFormat('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn-minimal btn-primary">{{ __('admin.view') }}</a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn-minimal">{{ __('admin.edit') }}</a>

                                    @if($user->id !== auth()->id())
                                        <button type="button" class="btn-minimal btn-danger" onclick="openDeleteModal({{ $user->id }}, '{{ $user->name }}')">
                                            {{ __('admin.delete') }}
                                        </button>
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

        {{-- Custom Pagination --}}
        @if ($users->hasPages())
            <div class="d-flex justify-content-center mt-4">
                <nav>
                    <ul class="pagination pagination-sm">
                        {{-- Previous --}}
                        @if ($users->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">Previous</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $users->appends(request()->query())->previousPageUrl() }}">Previous</a>
                            </li>
                        @endif

                        {{-- Page Numbers --}}
                        @for ($i = 1; $i <= $users->lastPage(); $i++)
                            @if ($i == $users->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $i }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $users->appends(request()->query())->url($i) }}">{{ $i }}</a>
                                </li>
                            @endif
                        @endfor

                        {{-- Next --}}
                        @if ($users->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $users->appends(request()->query())->nextPageUrl() }}">Next</a>
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

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteUserForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Warning:</strong> This action cannot be undone!
                    </div>
                    
                    <p>Are you sure you want to delete user <strong id="userName"></strong>?</p>
                    
                    <div class="mb-3">
                        <label for="transferTo" class="form-label">
                            <strong>Transfer content to:</strong>
                        </label>
                        <select name="transfer_to" id="transferTo" class="form-select">
                            <option value="">Auto-assign to first available admin</option>
                            @foreach(\App\Models\User::where('role', 'admin')->where('id', '!=', auth()->id())->get() as $admin)
                                <option value="{{ $admin->id }}">{{ $admin->name }} ({{ $admin->email }})</option>
                            @endforeach
                        </select>
                        <div class="form-text">
                            All posts and monuments created by this user will be transferred to the selected admin.
                            If no admin is selected, content will be orphaned.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openDeleteModal(userId, userName) {
    document.getElementById('userName').textContent = userName;
    document.getElementById('deleteUserForm').action = `/admin/users/${userId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
    modal.show();
}
</script>
@endpush
