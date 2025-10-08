<?php

return [
    'custom' => [
        'language' => [
            'required' => 'Vui lòng chọn ngôn ngữ.',
            'in' => 'Ngôn ngữ phải là Tiếng Anh hoặc Tiếng Việt.',
        ],
        'title' => [
            'required' => 'Vui lòng nhập tiêu đề.',
            'max' => 'Tiêu đề không được dài hơn :max ký tự.',
        ],
        'content' => [
            'required' => 'Vui lòng nhập nội dung.',
        ],
        'status' => [
            'required' => 'Vui lòng chọn trạng thái.',
            'in' => 'Trạng thái phải là nháp, chờ duyệt hoặc đã duyệt.',
        ],
        'image' => [
            'image' => 'Tệp tải lên phải là hình ảnh.',
            'mimes' => 'Định dạng cho phép: :values.',
            'max' => 'Kích thước ảnh không vượt quá :max kilobytes.',
        ],
        'translations' => [
            'required' => 'Vui lòng nhập nội dung bản dịch.',
        ],
        'reply' => [
            'required' => 'Vui lòng nhập nội dung phản hồi.',
        ],
        'key' => [
            'required' => 'Vui lòng nhập khóa cấu hình.',
            'unique' => 'Khóa cấu hình đã tồn tại.',
        ],
        'value' => [
            'required' => 'Vui lòng nhập giá trị cấu hình.',
        ],
        'email' => [
            'required' => 'Vui lòng nhập email.',
            'email' => 'Email không hợp lệ.',
        ],
        'password' => [
            'required' => 'Vui lòng nhập mật khẩu.',
            'confirmed' => 'Xác nhận mật khẩu không khớp.',
            'min' => 'Mật khẩu phải có ít nhất :min ký tự.',
        ],
        'current_password' => [
            'required' => 'Vui lòng nhập mật khẩu hiện tại.',
        ],
        'new_password' => [
            'required' => 'Vui lòng nhập mật khẩu mới.',
            'confirmed' => 'Xác nhận mật khẩu mới không khớp.',
            'min' => 'Mật khẩu mới phải có ít nhất :min ký tự.',
        ],
    ],
];



