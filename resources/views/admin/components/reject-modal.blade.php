<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">
                    <i class="bi bi-x-circle text-danger me-2"></i>{{ __('admin.reject_content') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">{{ __('admin.reason_for_rejection') }} <span class="text-danger">*</span></label>
                        <select class="form-select" id="rejection_reason" name="rejection_reason" required>
                            <option value="">{{ __('admin.select_rejection_reason') }}</option>
                            <option value="Inappropriate content">{{ __('admin.inappropriate_content') }}</option>
                            <option value="Poor quality images">{{ __('admin.poor_quality_images') }}</option>
                            <option value="Incomplete information">{{ __('admin.incomplete_information') }}</option>
                            <option value="Incorrect information">{{ __('admin.incorrect_information') }}</option>
                            <option value="Duplicate content">{{ __('admin.duplicate_content') }}</option>
                            <option value="Violates guidelines">{{ __('admin.violates_guidelines') }}</option>
                            <option value="Spam or promotional content">{{ __('admin.spam_content') }}</option>
                            <option value="Off-topic content">{{ __('admin.off_topic_content') }}</option>
                            <option value="Needs more detail">{{ __('admin.needs_more_detail') }}</option>
                            <option value="Other">{{ __('admin.other') }} ({{ __('admin.please_specify_below') }})</option>
                        </select>
                    </div>
                    <div class="mb-3" id="customReasonDiv" style="display: none;">
                        <label for="custom_reason" class="form-label">{{ __('admin.custom_reason') }}</label>
                        <textarea class="form-control" id="custom_reason" name="custom_reason" rows="3" placeholder="{{ __('admin.provide_specific_details') }}"></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>{{ __('admin.rejection_note') }}</strong> {{ __('admin.rejection_warning') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modern-secondary" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                    <button type="submit" class="btn btn-modern-danger">
                        <i class="bi bi-x-circle me-2"></i>{{ __('admin.reject_content_button') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rejectionReasonSelect = document.getElementById('rejection_reason');
    const customReasonDiv = document.getElementById('customReasonDiv');
    const customReasonTextarea = document.getElementById('custom_reason');
    const rejectForm = document.getElementById('rejectForm');

    // Show/hide custom reason field
    rejectionReasonSelect.addEventListener('change', function() {
        if (this.value === 'Other') {
            customReasonDiv.style.display = 'block';
            customReasonTextarea.required = true;
        } else {
            customReasonDiv.style.display = 'none';
            customReasonTextarea.required = false;
            customReasonTextarea.value = '';
        }
    });

    // Handle form submission
    rejectForm.addEventListener('submit', function(e) {
        const selectedReason = rejectionReasonSelect.value;
        const customReason = customReasonTextarea.value;

        if (selectedReason === 'Other' && !customReason.trim()) {
            e.preventDefault();
            alert('Please provide a custom reason for rejection.');
            customReasonTextarea.focus();
            return;
        }

        // Set the final rejection reason
        if (selectedReason === 'Other') {
            rejectionReasonSelect.value = customReason;
        }
    });
});

// Function to open reject modal with specific URL
function openRejectModal(url) {
    const rejectForm = document.getElementById('rejectForm');
    rejectForm.action = url;
    
    // Reset form
    rejectForm.reset();
    document.getElementById('customReasonDiv').style.display = 'none';
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}
</script>





