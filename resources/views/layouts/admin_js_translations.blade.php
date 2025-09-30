{{-- JavaScript Translations --}}
<script>
    // Global translations object for JavaScript
    window.translations = {
        // Common
        loading: "{{ __('admin.js_loading_image') }}",
        please_wait: "{{ __('admin.js_please_wait') }}",
        error: "{{ __('admin.error') }}",
        success: "{{ __('admin.success') }}",
        warning: "{{ __('admin.warning') }}",
        confirm: "{{ __('admin.confirm') }}",
        cancel: "{{ __('admin.cancel') }}",
        
        // File upload
        file_empty_corrupted: "{{ __('admin.js_file_empty_corrupted') }}",
        error_reading_file: "{{ __('admin.js_error_reading_file') }}",
        error_processing_form: "{{ __('admin.js_error_processing_form') }}",
        file_too_large: "{{ __('admin.js_file_too_large') }}",
        please_select_image: "{{ __('admin.js_please_select_image') }}",
        image_uploaded_successfully: "{{ __('admin.js_image_uploaded_successfully') }}",
        total_size_too_large: "{{ __('admin.js_total_size_too_large') }}",
        javascript_working: "{{ __('admin.js_javascript_working') }}",
        
        // Form validation
        please_fill_required: "{{ __('admin.js_please_fill_required') }}",
        form_data_too_large: "{{ __('admin.js_form_data_too_large') }}",
        current_size: "{{ __('admin.js_current_size') }}",
        maximum_allowed: "{{ __('admin.js_maximum_allowed') }}",
        
        // Confirm dialogs
        confirm_delete: "{{ __('admin.js_confirm_delete') }}",
        confirm_delete_post: "{{ __('admin.js_confirm_delete_post') }}",
        confirm_delete_monument: "{{ __('admin.js_confirm_delete_monument') }}",
        confirm_delete_feedback: "{{ __('admin.confirm_delete_feedback') }}",
        confirm_delete_image: "{{ __('admin.confirm_delete_image') }}",
        
        // Time greetings
        good_morning: "{{ __('admin.good_morning') }}",
        good_afternoon: "{{ __('admin.good_afternoon') }}",
        good_evening: "{{ __('admin.good_evening') }}",
        good_night: "{{ __('admin.good_night') }}",
    };
    
    // Helper function to get translation
    window.__ = function(key, replacements = {}) {
        let translation = window.translations[key] || key;
        
        // Replace placeholders
        for (let placeholder in replacements) {
            translation = translation.replace(':' + placeholder, replacements[placeholder]);
        }
        
        return translation;
    };
</script>

