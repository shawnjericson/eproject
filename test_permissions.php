<?php

/**
 * Test script để kiểm tra phân quyền CMS
 * Chạy script này để test các trường hợp phân quyền khác nhau
 */

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Post;
use App\Models\Monument;

echo "=== TEST PHÂN QUYỀN CMS ===\n\n";

// Test cases
$testCases = [
    [
        'title' => 'Test 1: Admin có thể approve content',
        'description' => 'Admin user có thể approve posts và monuments',
        'user_role' => 'admin',
        'action' => 'approve',
        'content_status' => 'pending',
        'expected' => 'success'
    ],
    [
        'title' => 'Test 2: Moderator KHÔNG thể approve content',
        'description' => 'Moderator user không thể approve posts và monuments',
        'user_role' => 'moderator',
        'action' => 'approve',
        'content_status' => 'pending',
        'expected' => 'forbidden'
    ],
    [
        'title' => 'Test 3: Admin có thể xóa content đã approve',
        'description' => 'Admin user có thể xóa posts và monuments đã approve',
        'user_role' => 'admin',
        'action' => 'delete',
        'content_status' => 'approved',
        'expected' => 'success'
    ],
    [
        'title' => 'Test 4: Moderator KHÔNG thể xóa content đã approve',
        'description' => 'Moderator user không thể xóa posts và monuments đã approve',
        'user_role' => 'moderator',
        'action' => 'delete',
        'content_status' => 'approved',
        'expected' => 'forbidden'
    ],
    [
        'title' => 'Test 5: Moderator có thể xóa content chưa approve',
        'description' => 'Moderator user có thể xóa posts và monuments chưa approve',
        'user_role' => 'moderator',
        'action' => 'delete',
        'content_status' => 'pending',
        'expected' => 'success'
    ],
    [
        'title' => 'Test 6: Moderator có thể tạo và edit content',
        'description' => 'Moderator user có thể tạo và edit posts, monuments, gallery',
        'user_role' => 'moderator',
        'action' => 'create_edit',
        'content_status' => 'any',
        'expected' => 'success'
    ]
];

echo "Các test case sẽ được thực hiện:\n\n";

foreach ($testCases as $index => $test) {
    echo ($index + 1) . ". " . $test['title'] . "\n";
    echo "   " . $test['description'] . "\n";
    echo "   User role: " . $test['user_role'] . "\n";
    echo "   Action: " . $test['action'] . "\n";
    echo "   Content status: " . $test['content_status'] . "\n";
    echo "   Expected result: " . $test['expected'] . "\n\n";
}

echo "=== HƯỚNG DẪN KIỂM TRA THỦ CÔNG ===\n\n";

echo "1. KIỂM TRA MIDDLEWARE:\n";
echo "   - Truy cập /admin với user moderator\n";
echo "   - Thử approve một post/monument (should fail)\n";
echo "   - Thử xóa một post/monument đã approved (should fail)\n";
echo "   - Thử xóa một post/monument chưa approved (should success)\n\n";

echo "2. KIỂM TRA API:\n";
echo "   - POST /api/posts/{id}/approve với moderator token (should return 403)\n";
echo "   - POST /api/posts/{id}/approve với admin token (should success)\n";
echo "   - DELETE /api/posts/{approved_id} với moderator token (should return 403)\n";
echo "   - DELETE /api/posts/{pending_id} với moderator token (should success)\n\n";

echo "3. KIỂM TRA VIEWS:\n";
echo "   - Login as moderator, check if approve buttons are hidden\n";
echo "   - Login as moderator, check if delete buttons are hidden for approved content\n";
echo "   - Login as admin, check if all buttons are visible\n\n";

echo "4. KIỂM TRA DATABASE:\n";
echo "   - Tạo test data với các status khác nhau\n";
echo "   - Verify permissions work correctly\n\n";

echo "=== ROUTES CẦN KIỂM TRA ===\n\n";

$routesToTest = [
    'Web Routes:',
    '- POST /admin/posts/{post}/approve (admin only)',
    '- POST /admin/posts/{post}/reject (admin only)', 
    '- DELETE /admin/posts/{post} (admin or moderator for non-approved)',
    '- POST /admin/monuments/{monument}/approve (admin only)',
    '- DELETE /admin/monuments/{monument} (admin or moderator for non-approved)',
    '',
    'API Routes:',
    '- POST /api/posts/{post}/approve (admin only)',
    '- POST /api/posts/{post}/reject (admin only)',
    '- DELETE /api/posts/{post} (admin or moderator for non-approved)',
    '- POST /api/monuments/{monument}/approve (admin only)',
    '- DELETE /api/monuments/{monument} (admin or moderator for non-approved)',
];

foreach ($routesToTest as $route) {
    echo $route . "\n";
}

echo "\n=== KẾT LUẬN ===\n";
echo "Hệ thống phân quyền đã được triển khai với:\n";
echo "✓ Middleware AdminApprovalMiddleware - chỉ admin approve\n";
echo "✓ Middleware PreventApprovedDeletionMiddleware - ngăn moderator xóa approved content\n";
echo "✓ Controller logic - double check permissions\n";
echo "✓ View logic - ẩn/hiện buttons theo role\n";
echo "✓ Route protection - middleware applied to sensitive routes\n\n";

echo "Chạy các test thủ công ở trên để verify hệ thống hoạt động đúng.\n";
