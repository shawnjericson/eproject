@extends('layouts.admin')

@section('title', __('admin.contact_message') . ' #' . $contact->id)

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h3 mb-1">
            <i class="bi bi-envelope-open"></i> {{ __('admin.contact_message') }} #{{ $contact->id }}
        </h1>
        <p class="text-muted mb-0">{{ $contact->subject }}</p>
    </div>
    <a href="{{ route('admin.contacts.index') }}" class="btn-minimal">
        <i class="bi bi-arrow-left"></i> {{ __('admin.back_to_list') }}
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

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Message Details Card -->
        <div class="card-minimal mb-4">
            <div class="card-header">
                <i class="bi bi-info-circle"></i> {{ __('admin.message_details') }}
            </div>
            <div class="card-body">
                <div class="info-row">
                    <div class="info-item">
                        <label><i class="bi bi-person"></i> {{ __('admin.from') }}:</label>
                        <span>{{ $contact->name }}</span>
                    </div>
                    <div class="info-item">
                        <label><i class="bi bi-envelope"></i> {{ __('admin.email') }}:</label>
                        <a href="mailto:{{ $contact->email }}" class="email-link">
                            {{ $contact->email }}
                        </a>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-item">
                        <label><i class="bi bi-calendar"></i> {{ __('admin.date') }}:</label>
                        <span>{{ $contact->created_at->format('F d, Y H:i:s') }}</span>
                    </div>
                    <div class="info-item">
                        <label><i class="bi bi-flag"></i> {{ __('admin.status') }}:</label>
                        @if($contact->status === 'new')
                            <span class="status-badge status-new">{{ __('admin.new') }}</span>
                        @elseif($contact->status === 'read')
                            <span class="status-badge status-read">{{ __('admin.read') }}</span>
                        @else
                            <span class="status-badge status-archived">{{ __('admin.archived') }}</span>
                        @endif
                    </div>
                </div>

                <div class="info-item">
                    <label><i class="bi bi-chat-left-text"></i> {{ __('admin.subject') }}:</label>
                    <span>{{ $contact->subject }}</span>
                </div>

                <hr>

                <div class="message-content">
                    <label><i class="bi bi-file-text"></i> {{ __('admin.message') }}:</label>
                    <div class="message-box">
                        {{ $contact->message }}
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Sidebar Actions -->
    <div class="col-lg-4">
        <!-- Quick Actions Card -->
        <div class="card-minimal mb-4">
            <div class="card-header">
                <i class="bi bi-lightning"></i> Quick Actions
            </div>
            <div class="card-body">
                <!-- Email Link -->
                <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->subject }}"
                   class="btn-minimal btn-primary w-100 mb-2">
                    <i class="bi bi-envelope"></i> Send Email
                </a>

                <!-- Copy Email -->
                <button type="button"
                        class="btn-minimal w-100 mb-3"
                        onclick="copyToClipboard('{{ $contact->email }}')">
                    <i class="bi bi-clipboard"></i> Copy Email
                </button>

                <hr>

                <!-- Status Update Form -->
                <form action="{{ route('admin.contacts.updateStatus', $contact) }}" method="POST" class="mb-3">
                    @csrf
                    @method('PATCH')
                    <label for="status" class="form-label">
                        <strong><i class="bi bi-flag"></i> {{ __('admin.update_status') }}</strong>
                    </label>
                    <select name="status" id="status" class="form-select mb-2">
                        <option value="new" {{ $contact->status === 'new' ? 'selected' : '' }}>{{ __('admin.new') }}</option>
                        <option value="read" {{ $contact->status === 'read' ? 'selected' : '' }}>{{ __('admin.read') }}</option>
                        <option value="archived" {{ $contact->status === 'archived' ? 'selected' : '' }}>{{ __('admin.archived') }}</option>
                    </select>
                    <button type="submit" class="btn-minimal btn-primary w-100">
                        <i class="bi bi-arrow-repeat"></i> {{ __('admin.update_status') }}
                    </button>
                </form>

                <hr>

                <!-- Delete Form -->
                <form action="{{ route('admin.contacts.destroy', $contact) }}"
                      method="POST"
                      onsubmit="return confirm('{{ __('admin.confirm_delete_message') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-minimal btn-danger w-100">
                        <i class="bi bi-trash"></i> {{ __('admin.delete') }} {{ __('admin.message') }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card-minimal">
            <div class="card-header">
                <i class="bi bi-info-circle"></i> Information
            </div>
            <div class="card-body">
                <div class="info-small">
                    <strong>Message ID:</strong> #{{ $contact->id }}
                </div>
                <div class="info-small">
                    <strong>Received:</strong> {{ $contact->created_at->diffForHumans() }}
                </div>
                <div class="info-small">
                    <strong>Last Updated:</strong> {{ $contact->updated_at->diffForHumans() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
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

/* Info Rows */
.info-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.info-item {
    margin-bottom: 1rem;
}

.info-item label {
    display: block;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
    font-size: 0.875rem;
}

.info-item span {
    color: var(--text-secondary);
}

.info-small {
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin-bottom: 0.75rem;
}

.info-small:last-child {
    margin-bottom: 0;
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

/* Email Link */
.email-link {
    color: var(--text-primary);
    text-decoration: none;
}

.email-link:hover {
    color: #3b82f6;
    text-decoration: underline;
}

/* Message Content */
.message-content label {
    display: block;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.message-box {
    background: var(--secondary-color);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 1rem;
    white-space: pre-wrap;
    color: var(--text-secondary);
    line-height: 1.6;
}

/* Card Header Variants */
.card-header-success {
    background: var(--success-color);
    color: white;
}

/* Button Loading State */
.spinner-border-sm {
    width: 1rem;
    height: 1rem;
    border-width: 0.15em;
}

@media (max-width: 768px) {
    .info-row {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Email copied to clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}

// Form submission handling
</script>
@endpush
@endsection

