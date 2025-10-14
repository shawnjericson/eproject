/**
 * React Maintenance Mode Integration Guide
 * 
 * This guide shows how to integrate maintenance mode detection into your React webapp
 */

// ============================================================================
// METHOD 1: Using the React Hook (Recommended)
// ============================================================================

import React from 'react';
import { useMaintenanceMode } from './js/react-maintenance-hook';

const App = () => {
    const { 
        isMaintenanceMode, 
        isChecking, 
        error, 
        manualCheck 
    } = useMaintenanceMode({
        apiUrl: '/api/health',
        maintenanceUrl: '/maintenance',
        checkInterval: 30000, // Check every 30 seconds
        retryCount: 3,
        autoRedirect: true
    });

    // Show loading state while checking
    if (isChecking) {
        return (
            <div style={{ 
                display: 'flex', 
                justifyContent: 'center', 
                alignItems: 'center', 
                height: '100vh' 
            }}>
                <div>Checking maintenance status...</div>
            </div>
        );
    }

    // Don't render app if in maintenance mode
    if (isMaintenanceMode) {
        return (
            <div style={{ 
                display: 'flex', 
                justifyContent: 'center', 
                alignItems: 'center', 
                height: '100vh',
                background: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                color: 'white'
            }}>
                <div style={{ textAlign: 'center' }}>
                    <h1>Website Under Maintenance</h1>
                    <p>We are currently performing maintenance. Please try again later.</p>
                    <button 
                        onClick={manualCheck}
                        style={{
                            background: 'white',
                            color: '#667eea',
                            border: 'none',
                            padding: '10px 20px',
                            borderRadius: '5px',
                            cursor: 'pointer'
                        }}
                    >
                        Check Again
                    </button>
                </div>
            </div>
        );
    }

    return (
        <div>
            {/* Your normal app content */}
            <h1>Welcome to My App</h1>
            {/* ... rest of your app */}
        </div>
    );
};

// ============================================================================
// METHOD 2: Using the React Component (Simpler)
// ============================================================================

import React from 'react';
import MaintenanceDetector from './js/react-maintenance-component';

const App = () => {
    return (
        <MaintenanceDetector
            apiUrl="/api/health"
            maintenanceUrl="/maintenance"
            checkInterval={30000}
            retryCount={3}
            autoRedirect={true}
            showNotification={true}
        >
            {/* Your normal app content */}
            <div>
                <h1>Welcome to My App</h1>
                {/* ... rest of your app */}
            </div>
        </MaintenanceDetector>
    );
};

// ============================================================================
// METHOD 3: Using Axios Interceptor (For API-heavy apps)
// ============================================================================

import axios from 'axios';

// Create axios instance
const api = axios.create({
    baseURL: '/api',
    timeout: 10000
});

// Add response interceptor to detect maintenance mode
api.interceptors.response.use(
    (response) => response,
    (error) => {
        // Check if it's a maintenance mode error
        if (error.response?.status === 503) {
            const data = error.response.data;
            if (data?.error === 'maintenance_mode') {
                // Show maintenance notification
                showMaintenanceNotification();
                
                // Redirect to maintenance page
                setTimeout(() => {
                    window.location.href = data.maintenance_page_url || '/maintenance';
                }, 2000);
            }
        }
        return Promise.reject(error);
    }
);

const showMaintenanceNotification = () => {
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

// Use the api instance in your components
const MyComponent = () => {
    const [data, setData] = React.useState(null);

    React.useEffect(() => {
        // This will automatically handle maintenance mode
        api.get('/posts')
            .then(response => setData(response.data))
            .catch(error => {
                // Error is already handled by interceptor
                console.error('API Error:', error);
            });
    }, []);

    return <div>{/* Your component content */}</div>;
};

// ============================================================================
// METHOD 4: Using Fetch with Error Handling
// ============================================================================

const fetchWithMaintenanceCheck = async (url, options = {}) => {
    try {
        const response = await fetch(url, {
            ...options,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                ...options.headers
            }
        });

        // Check for maintenance mode
        if (response.status === 503) {
            const data = await response.json();
            if (data.error === 'maintenance_mode') {
                showMaintenanceNotification();
                setTimeout(() => {
                    window.location.href = data.maintenance_page_url || '/maintenance';
                }, 2000);
                throw new Error('Maintenance mode active');
            }
        }

        return response;
    } catch (error) {
        console.error('Fetch error:', error);
        throw error;
    }
};

// Use in your components
const MyComponent = () => {
    const [data, setData] = React.useState(null);

    React.useEffect(() => {
        fetchWithMaintenanceCheck('/api/posts')
            .then(response => response.json())
            .then(data => setData(data))
            .catch(error => {
                console.error('Error:', error);
            });
    }, []);

    return <div>{/* Your component content */}</div>;
};

// ============================================================================
// INSTALLATION INSTRUCTIONS
// ============================================================================

/*
1. Copy the files to your React project:
   - public/js/react-maintenance-hook.js
   - public/js/react-maintenance-component.js

2. Choose one of the methods above based on your needs:
   - Method 1: Best for apps that need fine-grained control
   - Method 2: Best for simple apps that want automatic handling
   - Method 3: Best for API-heavy apps using Axios
   - Method 4: Best for apps using Fetch API

3. Import and use in your main App component:

   // For Method 1 (Hook)
   import { useMaintenanceMode } from './js/react-maintenance-hook';
   
   // For Method 2 (Component)
   import MaintenanceDetector from './js/react-maintenance-component';

4. Configure the options:
   - apiUrl: Your API health check endpoint (default: '/api/health')
   - maintenanceUrl: Your maintenance page URL (default: '/maintenance')
   - checkInterval: How often to check (default: 30000ms)
   - retryCount: How many retries before assuming maintenance (default: 3)
   - autoRedirect: Whether to automatically redirect (default: true)

5. Test by enabling maintenance mode in admin panel:
   - Go to /admin/settings
   - Enable "Maintenance Mode"
   - Your React app should redirect to maintenance page
*/

export default App;








