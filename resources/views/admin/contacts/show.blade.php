@extends('layouts.admin')

@section('title', 'Contact Message #' . $contact->id)

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h3 mb-1">
            <i class="bi bi-envelope-open"></i> Contact Message #{{ $contact->id }}
        </h1>
        <p class="text-muted mb-0">{{ $contact->subject }}</p>
    </div>
    <a href="{{ route('admin.contacts.index') }}" class="btn-minimal">
        <i class="bi bi-arrow-left"></i> Back to List
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
                <i class="bi bi-info-circle"></i> Message Details
            </div>
            <div class="card-body">
                <div class="info-row">
                    <div class="info-item">
                        <label><i class="bi bi-person"></i> From:</label>
                        <span>{{ $contact->name }}</span>
                    </div>
                    <div class="info-item">
                        <label><i class="bi bi-envelope"></i> Email:</label>
                        <a href="mailto:{{ $contact->email }}" class="email-link">
                            {{ $contact->email }}
                        </a>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-item">
                        <label><i class="bi bi-calendar"></i> Date:</label>
                        <span>{{ $contact->created_at->format('F d, Y H:i:s') }}</span>
                    </div>
                    <div class="info-item">
                        <label><i class="bi bi-flag"></i> Status:</label>
                        @if($contact->status === 'new')
                            <span class="status-badge status-new">New</span>
                        @elseif($contact->status === 'read')
                            <span class="status-badge status-read">Read</span>
                        @elseif($contact->status === 'replied')
                            <span class="status-badge status-replied">Replied</span>
                        @else
                            <span class="status-badge status-archived">Archived</span>
                        @endif
                    </div>
                </div>

                <div class="info-item">
                    <label><i class="bi bi-chat-left-text"></i> Subject:</label>
                    <span>{{ $contact->subject }}</span>
                </div>

                <hr>

                <div class="message-content">
                    <label><i class="bi bi-file-text"></i> Message:</label>
                    <div class="message-box">
                        {{ $contact->message }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Reply Section -->
        @if($contact->status !== 'replied')
            <div class="card-minimal">
                <div class="card-header card-header-success">
                    <i class="bi bi-reply"></i> Send Reply
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.contacts.reply', $contact) }}" method="POST" id="replyForm">
                        @csrf
                        <div class="mb-3">
                            <label for="reply" class="form-label">Your Reply <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('reply') is-invalid @enderror"
                                      id="reply"
                                      name="reply"
                                      rows="6"
                                      placeholder="Type your reply here...">{{ old('reply') }}</textarea>
                            @error('reply')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> This reply will be saved in the system. You can copy it to send via email.
                            </div>
                        </div>
                        <button type="submit" class="btn-minimal btn-success" id="submitBtn">
                            <span class="btn-text">
                                <i class="bi bi-send"></i> Send Reply
                            </span>
                            <span class="btn-loading" style="display: none;">
                                <span class="spinner-border spinner-border-sm me-2"></span>
                                Sending...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="card-minimal">
                <div class="card-header card-header-success">
                    <i class="bi bi-check-circle"></i> Reply Sent
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <label><i class="bi bi-person-badge"></i> Replied by:</label>
                        <span>{{ $contact->repliedBy->name ?? 'Unknown' }}</span>
                    </div>
                    <div class="info-item">
                        <label><i class="bi bi-calendar-check"></i> Replied at:</label>
                        <span>{{ $contact->replied_at->format('F d, Y H:i:s') }}</span>
                    </div>
                    <hr>
                    <div class="message-content">
                        <label><i class="bi bi-chat-quote"></i> Reply:</label>
                        <div class="message-box">
                            {{ $contact->admin_reply }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
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
                        <strong><i class="bi bi-flag"></i> Update Status</strong>
                    </label>
                    <select name="status" id="status" class="form-select mb-2">
                        <option value="new" {{ $contact->status === 'new' ? 'selected' : '' }}>New</option>
                        <option value="read" {{ $contact->status === 'read' ? 'selected' : '' }}>Read</option>
                        <option value="replied" {{ $contact->status === 'replied' ? 'selected' : '' }}>Replied</option>
                        <option value="archived" {{ $contact->status === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                    <button type="submit" class="btn-minimal btn-primary w-100">
                        <i class="bi bi-arrow-repeat"></i> Update Status
                    </button>
                </form>

                <hr>

                <!-- Delete Form -->
                <form action="{{ route('admin.contacts.destroy', $contact) }}"
                      method="POST"
                      onsubmit="return confirm('Are you sure you want to delete this message? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-minimal btn-danger w-100">
                        <i class="bi bi-trash"></i> Delete Message
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
const form = document.getElementById('replyForm');
const submitBtn = document.getElementById('submitBtn');

if (form && submitBtn) {
    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.querySelector('.btn-text').style.display = 'none';
        submitBtn.querySelector('.btn-loading').style.display = 'inline-block';
    });
}
</script>
@endpush
@endsection

