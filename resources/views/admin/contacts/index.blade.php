@extends('layouts.admin')

@section('title', __('admin.contact_messages'))

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h3 mb-1">
            <i class="bi bi-envelope"></i> {{ __('admin.contact_messages') }}
        </h1>
        <p class="text-muted mb-0">{{ __('admin.manage_contact_form_submissions') }}</p>
    </div>
</div>

<!-- Status Filter Buttons -->
<div class="filter-buttons mb-4">
    <a href="{{ route('admin.contacts.index') }}"
       class="filter-btn {{ !request('status') ? 'active' : '' }}">
        {{ __('admin.all') }} ({{ $contacts->total() }})
    </a>
    <a href="{{ route('admin.contacts.index', ['status' => 'new']) }}"
       class="filter-btn filter-btn-new {{ request('status') === 'new' ? 'active' : '' }}">
        <i class="bi bi-circle-fill"></i> {{ __('admin.new') }} ({{ $newCount }})
    </a>
    <a href="{{ route('admin.contacts.index', ['status' => 'read']) }}"
       class="filter-btn filter-btn-read {{ request('status') === 'read' ? 'active' : '' }}">
        <i class="bi bi-eye"></i> {{ __('admin.read') }} ({{ $readCount }})
    </a>
    <a href="{{ route('admin.contacts.index', ['status' => 'archived']) }}"
       class="filter-btn filter-btn-archived {{ request('status') === 'archived' ? 'active' : '' }}">
        <i class="bi bi-archive"></i> {{ __('admin.archived') }} ({{ $archivedCount }})
    </a>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="alert-success mb-4">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert-danger mb-4">
        <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
    </div>
@endif

<!-- Search Form -->
<div class="card-minimal mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.contacts.index') }}">
            <div class="row g-3">
                <div class="col-md-10">
                    <div class="position-relative">
                        <input type="text"
                               name="search"
                               class="form-control search-input"
                               placeholder="{{ __('admin.search_by_name_email_subject') }}"
                               value="{{ request('search') }}">
                        <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn-minimal btn-primary w-100">
                        <i class="bi bi-search"></i> {{ __('admin.search') }}
                    </button>
                </div>
            </div>
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
        </form>
    </div>
</div>

<!-- Messages Table -->
<div class="card-minimal">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table-minimal">
                <thead>
                    <tr>
                        <th style="width: 60px;">{{ __('admin.id') }}</th>
                        <th>{{ __('admin.name') }}</th>
                        <th>{{ __('admin.email') }}</th>
                        <th>{{ __('admin.subject') }}</th>
                        <th style="width: 120px;">{{ __('admin.status') }}</th>
                        <th style="width: 180px;">{{ __('admin.date') }}</th>
                        <th style="width: 180px;">{{ __('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                    <tr class="{{ $contact->status === 'new' ? 'row-new' : '' }}">
                        <td>
                            <strong>#{{ $contact->id }}</strong>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($contact->status === 'new')
                                    <span class="status-badge status-new me-2">NEW</span>
                                @endif
                                {{ $contact->name }}
                            </div>
                        </td>
                        <td>
                            <a href="mailto:{{ $contact->email }}" class="email-link">
                                {{ $contact->email }}
                            </a>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 300px;" title="{{ $contact->subject }}">
                                {{ $contact->subject }}
                            </div>
                        </td>
                        <td>
                            @if($contact->status === 'new')
                                <span class="status-badge status-new">
                                    <i class="bi bi-circle-fill"></i> {{ __('admin.new') }}
                                </span>
                            @elseif($contact->status === 'read')
                                <span class="status-badge status-read">
                                    <i class="bi bi-eye"></i> {{ __('admin.read') }}
                                </span>
                            @elseif($contact->status === 'replied')
                                <span class="status-badge status-replied">
                                    <i class="bi bi-reply"></i> {{ __('admin.replied') }}
                                </span>
                            @else
                                <span class="status-badge status-archived">
                                    <i class="bi bi-archive"></i> {{ __('admin.archived') }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">
                                {{ $contact->created_at->format('M d, Y') }}<br>
                                {{ $contact->created_at->format('H:i') }}
                            </small>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.contacts.show', $contact) }}"
                                   class="btn-minimal btn-primary"
                                   title="{{ __('admin.view') }}">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form action="{{ route('admin.contacts.destroy', $contact) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('{{ __('admin.confirm_delete_message') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-minimal btn-danger" title="{{ __('admin.delete') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <p>{{ __('admin.no_contact_messages_found') }}</p>
                                @if(request('search'))
                                    <a href="{{ route('admin.contacts.index') }}" class="btn-minimal btn-primary">
                                        {{ __('admin.clear_search') }}
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination -->
@if($contacts->hasPages())
    <div class="mt-4 d-flex justify-content-center">
        {{ $contacts->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
@endif

@push('styles')
<style>
/* Filter Buttons */
.filter-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.filter-btn {
    padding: 0.5rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-secondary);
    text-decoration: none;
    transition: all 0.2s ease;
    background: white;
}

.filter-btn:hover {
    border-color: var(--text-primary);
    color: var(--text-primary);
    background: var(--secondary-color);
}

.filter-btn.active {
    background: var(--text-primary);
    color: white;
    border-color: var(--text-primary);
}

.filter-btn-new.active {
    background: #3b82f6;
    border-color: #3b82f6;
}

.filter-btn-read.active {
    background: #06b6d4;
    border-color: #06b6d4;
}

.filter-btn-replied.active {
    background: var(--success-color);
    border-color: var(--success-color);
}

.filter-btn-archived.active {
    background: #6b7280;
    border-color: #6b7280;
}

/* Alert Messages */
.alert-success {
    padding: 1rem 1.25rem;
    background: #d1fae5;
    border: 1px solid var(--success-color);
    border-radius: 8px;
    color: #065f46;
}

.alert-danger {
    padding: 1rem 1.25rem;
    background: #fee2e2;
    border: 1px solid var(--danger-color);
    border-radius: 8px;
    color: #991b1b;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.status-new {
    background: #dbeafe;
    color: #1e40af;
}

.status-read {
    background: #cffafe;
    color: #155e75;
}

.status-replied {
    background: #d1fae5;
    color: #065f46;
}

.status-archived {
    background: #f3f4f6;
    color: #374151;
}

/* Table Row Highlight */
.row-new {
    background-color: rgba(59, 130, 246, 0.05);
}

/* Email Link */
.email-link {
    color: var(--text-primary);
    text-decoration: none;
}

.email-link:hover {
    color: #3b82f6;
    text-decoration: underline;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.action-buttons .btn-minimal {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    color: var(--text-secondary);
}

.empty-state i {
    font-size: 3rem;
    opacity: 0.5;
}

.empty-state p {
    margin-top: 1rem;
    margin-bottom: 0.5rem;
}
</style>
@endpush
@endsection

