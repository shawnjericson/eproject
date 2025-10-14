<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-trash text-danger me-2"></i>{{ __('admin.delete_content') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="deletion_reason" class="form-label">{{ __('admin.deletion_reason') }} <span class="text-danger">*</span></label>
                        <select class="form-select" id="deletion_reason" name="deletion_reason" required>
                            <option value="">{{ __('admin.select_deletion_reason') }}</option>
                            <option value="Inappropriate content">{{ __('admin.inappropriate_content') }}</option>
                            <option value="Poor quality images">{{ __('admin.poor_quality_images') }}</option>
                            <option value="Incomplete information">{{ __('admin.incomplete_information') }}</option>
                            <option value="Incorrect information">{{ __('admin.incorrect_information') }}</option>
                            <option value="Duplicate content">{{ __('admin.duplicate_content') }}</option>
                            <option value="Violates guidelines">{{ __('admin.violates_guidelines') }}</option>
                            <option value="Spam or promotional content">{{ __('admin.spam_content') }}</option>
                            <option value="Off-topic content">{{ __('admin.off_topic_content') }}</option>
                            <option value="Copyright violation">{{ __('admin.copyright_violation') }}</option>
                            <option value="User request">{{ __('admin.user_request') }}</option>
                            <option value="Other">{{ __('admin.other') }} ({{ __('admin.please_specify_below') }})</option>
                        </select>
                    </div>
                    <div class="mb-3" id="customDeletionReasonDiv" style="display: none;">
                        <label for="custom_deletion_reason" class="form-label">{{ __('admin.custom_deletion_reason') }}</label>
                        <textarea class="form-control" id="custom_deletion_reason" name="custom_deletion_reason" rows="3" placeholder="{{ __('admin.provide_specific_details') }}"></textarea>
                    </div>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>{{ __('admin.warning') }}:</strong> {{ __('admin.delete_warning_message') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modern-secondary" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                    <button type="submit" class="btn btn-modern-danger">
                        <i class="bi bi-trash me-2"></i>{{ __('admin.delete_content') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deletionReasonSelect = document.getElementById('deletion_reason');
    const customDeletionReasonDiv = document.getElementById('customDeletionReasonDiv');
    const customDeletionReasonTextarea = document.getElementById('custom_deletion_reason');
    const deleteForm = document.getElementById('deleteForm');

    // Show/hide custom deletion reason field
    deletionReasonSelect.addEventListener('change', function() {
        if (this.value === 'Other') {
            customDeletionReasonDiv.style.display = 'block';
            customDeletionReasonTextarea.required = true;
        } else {
            customDeletionReasonDiv.style.display = 'none';
            customDeletionReasonTextarea.required = false;
            customDeletionReasonTextarea.value = '';
        }
    });

    // Handle form submission
    deleteForm.addEventListener('submit', function(e) {
        const selectedReason = deletionReasonSelect.value;
        const customReason = customDeletionReasonTextarea.value;

        if (selectedReason === 'Other' && !customReason.trim()) {
            e.preventDefault();
            alert('{{ __('admin.please_provide_custom_deletion_reason') }}');
            customDeletionReasonTextarea.focus();
            return;
        }

        // Set the final deletion reason
        if (selectedReason === 'Other') {
            deletionReasonSelect.value = customReason;
        }
    });
});

// Function to open delete modal with specific URL
function openDeleteModal(url) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = url;
    
    // Reset form
    deleteForm.reset();
    document.getElementById('customDeletionReasonDiv').style.display = 'none';
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>




