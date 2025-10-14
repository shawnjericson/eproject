/**
 * React Hook for Maintenance Mode Detection
 * Use this hook in your React components to detect maintenance mode
 */

import { useState, useEffect, useCallback } from 'react';

export const useMaintenanceMode = (options = {}) => {
    const {
        apiUrl = '/api/health',
        maintenanceUrl = '/maintenance',
        checkInterval = 30000, // 30 seconds
        retryCount = 3,
        autoRedirect = true
    } = options;

    const [isMaintenanceMode, setIsMaintenanceMode] = useState(false);
    const [isChecking, setIsChecking] = useState(false);
    const [error, setError] = useState(null);
    const [retryCount, setRetryCount] = useState(0);

    const checkMaintenanceStatus = useCallback(async () => {
        setIsChecking(true);
        setError(null);

        try {
            const response = await fetch(apiUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                redirect: 'manual'
            });

            // If we get a 503 status, it's maintenance mode
            if (response.status === 503) {
                const data = await response.json();
                if (data.error === 'maintenance_mode') {
                    setIsMaintenanceMode(true);
                    setRetryCount(0);
                    
                    if (autoRedirect) {
                        // Show notification and redirect
                        showMaintenanceNotification();
                        setTimeout(() => {
                            window.location.href = data.maintenance_page_url || maintenanceUrl;
                        }, 2000);
                    }
                    return true;
                }
            }

            // If we get a successful response, reset retry count
            setIsMaintenanceMode(false);
            setRetryCount(0);
            return false;

        } catch (error) {
            console.warn('Maintenance check failed:', error);
            setError(error.message);
            setRetryCount(prev => prev + 1);
            
            // If we've exceeded retry count, assume maintenance mode
            if (retryCount >= retryCount) {
                setIsMaintenanceMode(true);
                if (autoRedirect) {
                    showMaintenanceNotification();
                    setTimeout(() => {
                        window.location.href = maintenanceUrl;
                    }, 2000);
                }
                return true;
            }
            
            return false;
        } finally {
            setIsChecking(false);
        }
    }, [apiUrl, maintenanceUrl, autoRedirect, retryCount]);

    const showMaintenanceNotification = () => {
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
    };

    const manualCheck = useCallback(() => {
        return checkMaintenanceStatus();
    }, [checkMaintenanceStatus]);

    const redirectToMaintenance = useCallback(() => {
        showMaintenanceNotification();
        setTimeout(() => {
            window.location.href = maintenanceUrl;
        }, 2000);
    }, [maintenanceUrl]);

    useEffect(() => {
        // Check immediately
        checkMaintenanceStatus();

        // Set up periodic checking
        const interval = setInterval(() => {
            checkMaintenanceStatus();
        }, checkInterval);

        return () => clearInterval(interval);
    }, [checkMaintenanceStatus, checkInterval]);

    return {
        isMaintenanceMode,
        isChecking,
        error,
        retryCount,
        manualCheck,
        redirectToMaintenance
    };
};

export default useMaintenanceMode;








