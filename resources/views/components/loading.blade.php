<!-- Global Loading Overlay -->
<div id="global-loading" class="loading-overlay" style="display: none;">
    <div class="loading-content">
        <div class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div class="loading-text">{{ __('admin.loading') }}...</div>
    </div>
</div>

<style>
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: center;
}

.loading-content {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    text-align: center;
    min-width: 200px;
}

.loading-spinner {
    margin-bottom: 1rem;
}

.loading-text {
    color: #6c757d;
    font-weight: 500;
    font-size: 1.1rem;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
    border-width: 0.3em;
}
</style>

<script>
// Global loading functions
window.showLoading = function() {
    document.getElementById('global-loading').style.display = 'flex';
};

window.hideLoading = function() {
    document.getElementById('global-loading').style.display = 'none';
};

// Auto-show loading on form submissions
document.addEventListener('DOMContentLoaded', function() {
    // Show loading on form submissions
    const forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        form.addEventListener('submit', function() {
            // Only show loading for non-AJAX forms
            if (!form.hasAttribute('data-ajax')) {
                window.showLoading();
            }
        });
    });

    // Show loading on link clicks (for navigation)
    const links = document.querySelectorAll('a:not([href^="#"]):not([target="_blank"]):not([data-no-loading])');
    links.forEach(function(link) {
        link.addEventListener('click', function(e) {
            // Only for internal links
            if (link.hostname === window.location.hostname) {
                window.showLoading();
            }
        });
    });

// Hide loading when page is fully loaded
window.addEventListener('load', function() {
    window.hideLoading();
});

// Intercept fetch requests for loading - TẠM TẮT ĐỂ TEST
// const originalFetch = window.fetch;
// window.fetch = function(...args) {
//     window.showLoading();
//     return originalFetch.apply(this, args)
//         .finally(() => {
//             // Small delay to prevent flickering
//             setTimeout(() => window.hideLoading(), 300);
//         });
// };
});
</script>
