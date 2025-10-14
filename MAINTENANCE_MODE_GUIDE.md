# Maintenance Mode System

Hệ thống Maintenance Mode cho phép admin bật/tắt chế độ bảo trì từ CMS admin panel, và frontend React sẽ tự động phát hiện và redirect người dùng đến trang bảo trì.

## Cách hoạt động

### 1. Backend (Laravel)
- **API Endpoint**: `/api/health` - Kiểm tra trạng thái maintenance mode
- **Settings**: Maintenance mode được lưu trong `site_settings` table với key `maintenance_mode`
- **Response**: 
  - Nếu maintenance mode = `true`: Trả về HTTP 503 với thông tin maintenance
  - Nếu maintenance mode = `false`: Trả về HTTP 200 với status healthy

### 2. Frontend (React)
- **MaintenanceDetector**: Component wrapper tự động kiểm tra maintenance status
- **useMaintenanceMode**: Hook để quản lý logic maintenance detection
- **MaintenancePage**: Component hiển thị trang bảo trì đẹp mắt
- **Auto-redirect**: Tự động redirect khi phát hiện maintenance mode

## Cách sử dụng

### 1. Bật Maintenance Mode từ Admin Panel
1. Đăng nhập vào CMS admin panel
2. Vào Settings
3. Bật "Chế độ bảo trì" (Maintenance Mode)
4. Lưu settings

### 2. Frontend sẽ tự động:
- Kiểm tra `/api/health` mỗi 30 giây
- Hiển thị notification khi phát hiện maintenance mode
- Redirect đến trang maintenance sau 2 giây
- Hiển thị trang maintenance đẹp mắt với thông tin đa ngôn ngữ

### 3. Tắt Maintenance Mode
1. Vào Admin Panel → Settings
2. Tắt "Chế độ bảo trì"
3. Frontend sẽ tự động phát hiện và quay lại trang bình thường

## Cấu hình

### MaintenanceDetector Props
```jsx
<MaintenanceDetector
  apiUrl="/api/health"           // API endpoint để check
  maintenanceUrl="/maintenance"  // URL trang maintenance
  checkInterval={30000}         // Thời gian check (ms)
  retryCount={3}                // Số lần retry khi lỗi
  autoRedirect={true}           // Tự động redirect
  showNotification={true}       // Hiển thị notification
>
  {/* App content */}
</MaintenanceDetector>
```

### useMaintenanceMode Hook
```jsx
const {
  isMaintenanceMode,    // Boolean: có đang maintenance không
  isChecking,          // Boolean: đang check không
  error,               // String: lỗi nếu có
  retryCount,          // Number: số lần retry hiện tại
  manualCheck,         // Function: check thủ công
  redirectToMaintenance // Function: redirect thủ công
} = useMaintenanceMode(options);
```

## Files đã tạo/sửa

### Backend
- `app/Http/Controllers/Api/HealthController.php` - Cập nhật logic check maintenance

### Frontend
- `frontend/src/components/Maintenance/MaintenancePage.jsx` - Trang maintenance
- `frontend/src/components/Maintenance/MaintenanceDetector.jsx` - Component detector
- `frontend/src/hooks/useMaintenanceMode.js` - Hook quản lý maintenance
- `frontend/src/App.js` - Tích hợp MaintenanceDetector

## Tính năng

✅ **Auto-detection**: Tự động phát hiện maintenance mode  
✅ **Auto-redirect**: Tự động redirect đến trang maintenance  
✅ **Multi-language**: Hỗ trợ đa ngôn ngữ (VI/EN)  
✅ **Beautiful UI**: Giao diện maintenance page đẹp mắt  
✅ **Real-time**: Cập nhật real-time khi admin bật/tắt  
✅ **Retry logic**: Xử lý lỗi network với retry mechanism  
✅ **Development mode**: Debug info trong development  
✅ **Notification**: Hiển thị thông báo trước khi redirect  

## Testing

### Test bật maintenance mode:
1. Bật maintenance mode trong admin panel
2. Refresh frontend → Sẽ redirect đến `/maintenance`
3. Tắt maintenance mode trong admin panel
4. Frontend sẽ tự động quay lại trang bình thường

### Test API endpoint:
```bash
# Khi maintenance mode = false
curl http://localhost:8000/api/health
# Response: {"status":"healthy","timestamp":"...","message":"Service is running normally"}

# Khi maintenance mode = true  
curl http://localhost:8000/api/health
# Response: {"error":"maintenance_mode","message":"Website is currently under maintenance",...}
```



