/**
 * React Component for Maintenance Mode Detection
 * Use this component in your React app to automatically handle maintenance mode
 */

import React from 'react';
import { useMaintenanceMode } from './react-maintenance-hook';

const MaintenanceDetector = ({ 
    children, 
    apiUrl = '/api/health',
    maintenanceUrl = '/maintenance',
    checkInterval = 30000,
    retryCount = 3,
    autoRedirect = true,
    showNotification = true
}) => {
    const { 
        isMaintenanceMode, 
        isChecking, 
        error, 
        retryCount: currentRetries,
        manualCheck,
        redirectToMaintenance 
    } = useMaintenanceMode({
        apiUrl,
        maintenanceUrl,
        checkInterval,
        retryCount,
        autoRedirect
    });

    // Show maintenance notification if enabled
    React.useEffect(() => {
        if (isMaintenanceMode && showNotification) {
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
    }, [isMaintenanceMode, showNotification]);

    // Don't render children if in maintenance mode
    if (isMaintenanceMode) {
        return null;
    }

    return (
        <div>
            {children}
            {/* Optional: Show maintenance status in development */}
            {process.env.NODE_ENV === 'development' && (
                <div style={{
                    position: 'fixed',
                    bottom: '10px',
                    right: '10px',
                    background: 'rgba(0,0,0,0.8)',
                    color: 'white',
                    padding: '10px',
                    borderRadius: '5px',
                    fontSize: '12px',
                    zIndex: 1000
                }}>
                    <div>Maintenance Check: {isChecking ? 'Checking...' : 'OK'}</div>
                    {error && <div>Error: {error}</div>}
                    {currentRetries > 0 && <div>Retries: {currentRetries}</div>}
                    <button 
                        onClick={manualCheck}
                        style={{
                            background: '#007bff',
                            color: 'white',
                            border: 'none',
                            padding: '5px 10px',
                            borderRadius: '3px',
                            cursor: 'pointer',
                            marginTop: '5px'
                        }}
                    >
                        Check Now
                    </button>
                </div>
            )}
        </div>
    );
};

export default MaintenanceDetector;








