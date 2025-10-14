/**
 * Maintenance Mode Detection Script
 * This script should be included in your frontend webapp to detect maintenance mode
 * and redirect users to the maintenance page.
 */

class MaintenanceDetector {
    constructor(options = {}) {
        this.apiUrl = options.apiUrl || '/api/health';
        this.maintenanceUrl = options.maintenanceUrl || '/maintenance';
        this.checkInterval = options.checkInterval || 30000; // 30 seconds
        this.retryCount = options.retryCount || 3;
        this.currentRetries = 0;
        
        this.startDetection();
    }

    async checkMaintenanceStatus() {
        try {
            const response = await fetch(this.apiUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                // Don't follow redirects to detect 503 status
                redirect: 'manual'
            });

            // If we get a 503 status, it's maintenance mode
            if (response.status === 503) {
                const data = await response.json();
                if (data.error === 'maintenance_mode') {
                    this.redirectToMaintenance(data.maintenance_page_url);
                    return true;
                }
            }

            // If we get a successful response, reset retry count
            this.currentRetries = 0;
            return false;

        } catch (error) {
            console.warn('Maintenance check failed:', error);
            this.currentRetries++;
            
            // If we've exceeded retry count, assume maintenance mode
            if (this.currentRetries >= this.retryCount) {
                this.redirectToMaintenance();
                return true;
            }
            
            return false;
        }
    }

    redirectToMaintenance(customUrl = null) {
        const maintenanceUrl = customUrl || this.maintenanceUrl;
        
        // Show a brief message before redirecting
        this.showMaintenanceMessage();
        
        // Redirect after a short delay
        setTimeout(() => {
            window.location.href = maintenanceUrl;
        }, 2000);
    }

    showMaintenanceMessage() {
        // Create a maintenance notification
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 15px;
            text-align: center;
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
            font-weight: 600;
            z-index: 9999;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        `;
        
        notification.innerHTML = `
            <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span>Website is under maintenance. Redirecting to maintenance page...</span>
            </div>
        `;
        
        document.body.insertBefore(notification, document.body.firstChild);
    }

    async startDetection() {
        // Check immediately
        await this.checkMaintenanceStatus();
        
        // Set up periodic checking
        setInterval(async () => {
            await this.checkMaintenanceStatus();
        }, this.checkInterval);
    }

    // Method to manually check maintenance status
    async manualCheck() {
        return await this.checkMaintenanceStatus();
    }
}

// Auto-initialize if not in admin area
if (!window.location.pathname.startsWith('/admin')) {
    // Initialize maintenance detector
    window.maintenanceDetector = new MaintenanceDetector({
        apiUrl: '/api/health', // Change this to your API health check endpoint
        maintenanceUrl: '/maintenance',
        checkInterval: 30000, // Check every 30 seconds
        retryCount: 3
    });
}

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = MaintenanceDetector;
}

// Export for ES6 modules
if (typeof exports !== 'undefined') {
    exports.MaintenanceDetector = MaintenanceDetector;
}








