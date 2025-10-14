<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel') - Global Heritage</title>

    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon_io/favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon_io/favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon_io/favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon_io/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon_io/android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('favicon_io/android-chrome-512x512.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom Modern Styles -->
    <style>
        :root {
            --primary-color: #374151;
            --primary-dark: #1f2937;
            --secondary-color: #f9fafb;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --sidebar-width: 260px;
            --accent-color: #9ca3af;
        }

        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background-color: #ffffff;
            color: var(--text-primary);
            font-size: 14px;
            line-height: 1.6;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Top Navigation Bar */
        .top-navbar {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            height: 70px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            backdrop-filter: blur(10px);
        }

        .top-navbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 0 2rem;
            max-width: 1400px;
            margin: 0 auto
        }

        .top-navbar-left {
            display: flex;
            align-items: center;
            gap: 1.5rem
        }

        .top-navbar-right {
            display: flex;
            align-items: center;
            gap: 1rem
        }

        .sidebar-toggle {
            background: var(--primary-color);
            border: none;
            border-radius: 12px;
            padding: .75rem;
            color: #fff;
            transition: .3s;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .15);
            font-size: 1.1rem
        }

        .sidebar-toggle:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, .2)
        }

        .page-title {
            color: var(--text-primary);
            font-weight: 700;
            font-size: 1.4rem;
            margin: 0;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text
        }

        .top-navbar .btn {
            border: none;
            border-radius: 12px;
            padding: .75rem 1.5rem;
            font-weight: 600;
            font-size: .9rem;
            transition: .3s;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .1);
            position: relative;
            overflow: hidden
        }

        .top-navbar .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .2), transparent);
            transition: left .5s
        }

        .top-navbar .btn:hover::before {
            left: 100%
        }

        .top-navbar .btn-outline-secondary {
            background: #fff;
            color: var(--text-primary);
            border: 2px solid var(--border-color)
        }

        .top-navbar .btn-outline-secondary:hover {
            background: var(--primary-color);
            color: #fff;
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, .15)
        }

        .top-navbar .btn-outline-primary {
            background: var(--primary-color);
            color: #fff;
            border: 2px solid var(--primary-color)
        }

        .top-navbar .btn-outline-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, .2)
        }

        .top-navbar .dropdown-menu {
            background: #fff;
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, .15);
            padding: .5rem;
            margin-top: .5rem;
            min-width: 200px;
            backdrop-filter: blur(10px)
        }

        .top-navbar .dropdown-item {
            border-radius: 10px;
            padding: .75rem 1rem;
            margin: .25rem 0;
            font-weight: 500;
            transition: .2s;
            color: var(--text-primary)
        }

        .top-navbar .dropdown-item:hover {
            background: var(--primary-color);
            color: #fff;
            transform: translateX(4px)
        }

        .top-navbar .dropdown-item.active {
            background: var(--primary-color);
            color: #fff
        }

        .top-navbar .dropdown-item-text {
            color: var(--text-secondary);
            font-size: .85rem;
            font-weight: 600;
            padding: .5rem 1rem
        }

        .top-navbar .dropdown-divider {
            margin: .5rem 0;
            border-color: var(--border-color)
        }

        .top-navbar .user-profile-btn {
            background: #fff;
            border: 2px solid var(--border-color);
            border-radius: 50px;
            padding: .5rem 1rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            transition: .3s;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .1)
        }

        .top-navbar .user-profile-btn:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, .15)
        }

        .top-navbar .user-profile-btn img {
            width: 36px;
            height: 36px;
            border: 2px solid var(--primary-color)
        }

        /* Sidebar */
        .modern-sidebar {
            background: #fff;
            border-right: 1px solid var(--border-color);
            box-shadow: none;
            width: var(--sidebar-width);
            height: calc(100vh - 70px);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            top: 70px;
            position: fixed;
            z-index: 1000
        }

        .modern-sidebar .container-fluid {
            height: 100%;
            display: flex;
            flex-direction: column
        }

        .modern-sidebar .navbar-brand {
            color: var(--text-primary) !important;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 1.5rem 1rem;
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex-shrink: 0;
            text-decoration: none
        }

        .modern-sidebar .nav-link {
            color: var(--text-secondary) !important;
            padding: .75rem 1rem;
            margin: .25rem .5rem;
            border-radius: 6px;
            transition: .2s;
            font-weight: 500;
            font-size: .9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-decoration: none
        }

        .modern-sidebar .nav-link:hover {
            background: var(--secondary-color);
            color: var(--text-primary) !important
        }

        .modern-sidebar .dropdown-menu {
            background: #fff;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, .1);
            border-radius: 8px;
            margin-left: 1rem;
            z-index: 1060
        }

        .modern-sidebar .dropdown-item {
            color: var(--text-primary);
            padding: .5rem 1rem;
            border-radius: 6px;
            margin: .25rem;
            font-weight: 500
        }

        .modern-sidebar .dropdown-item:hover {
            background: var(--primary-color);
            color: #fff
        }

        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: 70px;
            min-height: calc(100vh - 70px);
            padding: 2rem;
            overflow-x: hidden
        }

        .modern-card {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, .1);
            transition: .3s
        }

        .modern-card:hover {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, .1);
            transform: translateY(-2px)
        }

        .modern-card .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 1.5rem;
            font-weight: 600;
            color: var(--text-primary)
        }

        .modern-card .card-body {
            padding: 1.5rem
        }

        .btn-modern-primary {
            background: var(--text-primary);
            border: none;
            border-radius: 6px;
            padding: .75rem 1.5rem;
            font-weight: 500;
            color: #fff;
            transition: .2s
        }

        .btn-modern-primary:hover {
            background: var(--primary-dark);
            color: #fff
        }

        .btn-modern-secondary {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: .75rem 1.5rem;
            font-weight: 500;
            color: var(--text-primary);
            transition: .2s
        }

        .btn-modern-secondary:hover {
            border-color: var(--text-primary);
            color: var(--text-primary)
        }

        /* thêm màu info cho nút xem nội dung (tuỳ chọn) */
        .btn-modern-info {
            background: #0ea5e9;
            border: none;
            border-radius: 6px;
            padding: .75rem 1.5rem;
            font-weight: 500;
            color: #fff
        }

        .btn-minimal {
            background: transparent;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            padding: .5rem 1rem;
            font-size: .875rem;
            font-weight: 500;
            color: var(--text-secondary);
            transition: .2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .5rem
        }

        .btn-minimal:hover {
            border-color: var(--text-primary);
            color: var(--text-primary);
            background: var(--secondary-color)
        }

        .btn-minimal.btn-primary {
            color: var(--text-primary);
            border-color: var(--text-primary)
        }

        .btn-minimal.btn-primary:hover {
            background: var(--text-primary);
            color: #fff
        }

        .btn-minimal.btn-success {
            color: var(--success-color);
            border-color: var(--success-color)
        }

        .btn-minimal.btn-success:hover {
            background: var(--success-color);
            color: #fff
        }

        .btn-minimal.btn-warning {
            color: var(--warning-color);
            border-color: var(--warning-color)
        }

        .btn-minimal.btn-warning:hover {
            background: var(--warning-color);
            color: #fff
        }

        .btn-minimal.btn-danger {
            color: var(--danger-color);
            border-color: var(--danger-color)
        }

        .btn-minimal.btn-danger:hover {
            background: var(--danger-color);
            color: #fff
        }

        .table-minimal {
            border: none
        }

        .table-minimal th {
            border-bottom: 2px solid var(--border-color);
            font-weight: 600;
            color: var(--text-primary);
            background: transparent;
            padding: 1rem .75rem
        }

        .table-minimal td {
            border-bottom: 1px solid var(--border-color);
            padding: .75rem;
            vertical-align: middle
        }

        .table-minimal tbody tr:hover {
            background: var(--secondary-color)
        }

        .card-minimal {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-shadow: none;
            background: #fff
        }

        .card-minimal .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem;
            font-weight: 600;
            color: var(--text-primary)
        }

        .card-minimal .card-body {
            padding: 1.25rem
        }

        .pagination-sm .page-link {
            padding: .35rem .65rem;
            font-size: .8125rem;
            line-height: 1.3;
            border-radius: 4px;
            border: 1px solid #dee2e6;
            color: #374151;
            background-color: #fff;
            margin: 0 3px;
            min-width: 32px;
            text-align: center;
            font-weight: 500;
            transition: .15s
        }

        .pagination-sm .page-link:hover {
            background-color: #f3f4f6;
            border-color: #374151;
            color: #374151;
            text-decoration: none
        }

        .pagination-sm .page-item.active .page-link {
            background-color: #374151;
            border-color: #374151;
            color: #fff;
            font-weight: 600
        }

        .pagination-sm .page-item.disabled .page-link {
            color: #9ca3af;
            background-color: #f9fafb;
            border-color: #e5e7eb;
            opacity: .7;
            cursor: not-allowed
        }

        .pagination-sm {
            margin: 0
        }

        .editor-toolbar {
            background: var(--secondary-color);
            border: 1px solid var(--border-color);
            border-radius: 8px 8px 0 0;
            padding: 1rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: center
        }

        .editor-section {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 1.5rem
        }

        .editor-section-header {
            background: var(--secondary-color);
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            color: var(--text-primary);
            border-radius: 8px 8px 0 0
        }

        .editor-section-body {
            padding: 1.25rem
        }

        .image-gallery-item {
            position: relative;
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            transition: .2s;
            margin-bottom: 1rem;
            cursor: pointer
        }

        .image-gallery-item:hover {
            border-color: var(--text-primary);
            background: var(--secondary-color)
        }

        .image-gallery-item.drag-over {
            border-color: var(--success-color);
            border-style: solid;
            background: rgba(16, 185, 129, .1);
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(16, 185, 129, .3)
        }

        .image-gallery-item.drag-over::before {
            content: "📁 Drop image here";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--success-color);
            color: #fff;
            padding: .5rem 1rem;
            border-radius: 4px;
            font-size: .875rem;
            font-weight: 500;
            z-index: 10
        }

        .image-gallery-item.has-image {
            border-style: solid;
            border-color: var(--success-color);
            padding: 0
        }

        .image-gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 6px
        }

        .image-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, .7);
            color: #fff;
            padding: .5rem;
            font-size: .875rem;
            border-radius: 0 0 6px 6px
        }

        .toc-preview {
            background: var(--secondary-color);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem
        }

        .toc-preview h6 {
            margin-bottom: .75rem;
            color: var(--text-primary);
            font-weight: 600
        }

        .toc-preview ul {
            list-style: none;
            padding-left: 0;
            margin: 0
        }

        .toc-preview li {
            padding: .25rem 0;
            color: var(--text-secondary);
            font-size: .875rem
        }

        .toc-preview li.h2 {
            font-weight: 500;
            color: var(--text-primary)
        }

        .toc-preview li.h3 {
            padding-left: 1rem
        }

        .toc-preview li.h4 {
            padding-left: 2rem
        }

        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 10px;
            padding: .75rem 1rem;
            font-weight: 500;
            transition: .3s
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .1)
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: .5rem
        }

        .page-header {
            background: #fff;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 4px rgba(0, 0, 0, .05)
        }

        .page-header h1 {
            font-weight: 700;
            color: var(--text-primary);
            margin: 0
        }

        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            transition: .3s
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, .1)
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem
        }

        .stat-card.primary .stat-icon {
            background: var(--text-primary);
            color: #fff
        }

        .stat-card.success .stat-icon {
            background: var(--success-color);
            color: #fff
        }

        .stat-card.warning .stat-icon {
            background: var(--warning-color);
            color: #fff
        }

        .stat-card.danger .stat-icon {
            background: var(--danger-color);
            color: #fff
        }

        .clickable-card {
            cursor: pointer;
            transition: .3s
        }

        .clickable-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, .15)
        }

        .clickable-card:active {
            transform: translateY(-2px)
        }

        .nav-main {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 1rem 0;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e0 #f7fafc;
            min-height: 0;
            max-height: none
        }

        .nav-main::-webkit-scrollbar {
            width: 6px
        }

        .nav-main::-webkit-scrollbar-track {
            background: #f7fafc;
            border-radius: 3px
        }

        .nav-main::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 3px
        }

        .nav-main::-webkit-scrollbar-thumb:hover {
            background: #a0aec0
        }

        .nav-bottom {
            flex-shrink: 0;
            border-top: 1px solid var(--border-color);
            padding: 1rem 0;
            background: #f8f9fa
        }

        .modern-sidebar .nav-item {
            margin-bottom: .25rem
        }

        .modern-sidebar .nav-link {
            position: relative;
            transition: .3s
        }

        .modern-sidebar .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: var(--primary-color);
            transform: scaleY(0);
            transition: transform .3s
        }

        .modern-sidebar .nav-link:hover::before,
        .modern-sidebar .nav-link.active::before {
            transform: scaleY(1)
        }

        .notification-dropdown {
            width: 320px !important;
            max-height: 400px;
            overflow-y: auto;
            overflow-x: hidden
        }

        .notification-item {
            padding: .75rem !important;
            border-radius: 8px;
            margin: .25rem 0;
            transition: .2s
        }

        .notification-item.unread {
            background-color: #f8f9fa;
            border-left: 3px solid var(--primary-color)
        }

        .notification-item:hover {
            background-color: #e9ecef
        }

        .notification-content {
            display: flex;
            align-items: flex-start;
            gap: .75rem;
            width: 100%
        }

        .notification-icon {
            flex-shrink: 0;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px
        }

        .notification-text {
            flex: 1;
            min-width: 0;
            overflow: hidden
        }

        .notification-title {
            font-weight: 600;
            font-size: .9rem;
            color: var(--text-primary);
            margin-bottom: .25rem;
            word-wrap: break-word;
            line-height: 1.3
        }

        .notification-message {
            font-size: .8rem;
            color: var(--text-secondary);
            margin-bottom: .25rem;
            word-wrap: break-word;
            line-height: 1.3
        }

        .notification-time {
            font-size: .75rem;
            color: #6c757d
        }

        .notification-dot {
            flex-shrink: 0;
            margin-left: .5rem
        }

        #notificationBell {
            position: relative;
            overflow: visible
        }

        #notificationBell .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: .7rem;
            padding: .25em .4em;
            min-width: 18px;
            height: 18px;
            line-height: 1;
            z-index: 1000;
            border: 2px solid #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .2)
        }

        .notification-detail {
            padding: 1rem 0
        }

        .notification-icon-large {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #f8f9fa
        }

        .notification-message-full {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid var(--primary-color);
            margin: 1rem 0
        }

        .notification-message-full p {
            margin: 0;
            line-height: 1.6
        }

        #notificationModal .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, .15)
        }

        #notificationModal .modal-header {
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
            border-bottom: 1px solid var(--border-color);
            border-radius: 16px 16px 0 0
        }

        #notificationModal .modal-footer {
            background-color: #f8f9fa;
            border-top: 1px solid var(--border-color);
            border-radius: 0 0 16px 16px
        }

        .search-input {
            position: relative;
            transition: .3s;
            padding-right: 40px
        }

        .search-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .1)
        }

        .search-input+.bi-search {
            transition: color .3s
        }

        .search-input:focus+.bi-search {
            color: #3b82f6
        }

        @media (max-width: 768px) {
            .modern-sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 1000;
                height: 100vh
            }

            .main-content {
                margin-left: 0;
                padding: 1rem
            }

            .text-truncate {
                max-width: 100px
            }
        }
    </style>

    @stack('styles')

    {{-- JavaScript Translations --}}
    @include('layouts.admin_js_translations')
</head>

<body>
    <!-- Top Navigation Bar -->
    <nav class="top-navbar">
        <div class="top-navbar-content">
            <div class="top-navbar-left">
                <button class="sidebar-toggle d-lg-none" type="button">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="page-title mb-0">@yield('title', 'Admin Panel')</h5>
            </div>
            <div class="top-navbar-right">
                <!-- Language Picker -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        @if(app()->getLocale() == 'vi') {{ __('admin.vietnamese') }} @else {{ __('admin.english') }} @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}">
                                {{ __('admin.english') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ app()->getLocale() == 'vi' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['lang' => 'vi']) }}">
                                {{ __('admin.vietnamese') }}
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Notification Bell -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle position-relative" type="button" data-bs-toggle="dropdown" id="notificationBell">
                        <i class="bi bi-bell"></i>
                        @php
                        $unreadCount = auth()->user() ? auth()->user()->unread_notifications_count : 0;
                        @endphp
                        @if($unreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                        @endif
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown">
                        <li>
                            <h6 class="dropdown-header">{{ __('admin.notifications') }}</h6>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        @php
                        $notifications = auth()->user()->userNotifications()->orderBy('created_at', 'desc')->limit(10)->get();
                        @endphp

                        @forelse($notifications as $notification)
                        <li>
                            <a href="#" class="dropdown-item notification-item {{ !$notification->is_read ? 'unread' : '' }}"
                                data-notification-id="{{ $notification->id }}"
                                data-full-message="{{ addslashes($notification->message) }}"
                                data-url="{{ $notification->data['url'] ?? '#' }}">
                                <div class="notification-content">
                                    <div class="notification-icon">
                                        @switch($notification->type)
                                        @case('post_rejected') <i class="bi bi-x-circle text-warning"></i> @break
                                        @case('post_deleted') <i class="bi bi-trash text-danger"></i> @break
                                        @case('monument_rejected') <i class="bi bi-x-circle text-warning"></i> @break
                                        @case('monument_deleted') <i class="bi bi-trash text-danger"></i> @break
                                        @case('monument_created_by_moderator') <i class="bi bi-plus-circle text-success"></i> @break
                                        @case('post_created_by_moderator') <i class="bi bi-plus-circle text-success"></i> @break
                                        @case('user_registration') <i class="bi bi-person-plus text-info"></i> @break
                                        @default <i class="bi bi-bell text-primary"></i>
                                        @endswitch
                                    </div>
                                    <div class="notification-text">
                                        @php
                                        $displayTitle = $notification->title;
                                        $displayMessage = Str::limit($notification->message, 60);

                                        if (strpos($notification->title, ' / ') !== false) {
                                        $parts = explode(' / ', $notification->title);
                                        $displayTitle = app()->getLocale() === 'vi' ? $parts[0] : $parts[1];
                                        }
                                        if (strpos($notification->message, "\n\n") !== false) {
                                        $parts = explode("\n\n", $notification->message);
                                        $displayMessage = Str::limit(app()->getLocale() === 'vi' ? $parts[0] : $parts[1], 60);
                                        }
                                        @endphp
                                        <div class="notification-title">{{ $displayTitle }}</div>
                                        <div class="notification-message">{{ $displayMessage }}</div>
                                        <div class="notification-time">{{ $notification->created_at->diffForHumans() }}</div>
                                    </div>
                                    @if(!$notification->is_read)
                                    <div class="notification-dot">
                                        <span class="badge bg-primary rounded-pill">•</span>
                                    </div>
                                    @endif
                                </div>
                            </a>
                        </li>
                        @empty
                        <li><span class="dropdown-item-text text-center text-muted">{{ __('admin.no_notifications') }}</span></li>
                        @endforelse

                        @if($notifications->count() > 0)
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a href="#" class="dropdown-item text-center" onclick="markAllAsRead()">
                                <i class="bi bi-check-all me-1"></i>{{ __('admin.mark_all_read') }}
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>

                <!-- View Site Link -->
                <a href="https://eproject-1-fe.izido.tech/" target="_blank" class="btn btn-outline-primary">
                    <i class="bi bi-eye me-1"></i>{{ __('admin.view_site') }}
                </a>

                <!-- User Profile Dropdown -->
                <div class="dropdown">
                    <button class="user-profile-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <img src="{{ auth()->user()?->avatar_url ?? 'https://placehold.co/600x400/EEE/31343C' }}"
                            alt="{{ auth()->user()?->name }}"
                            class="rounded-circle">
                        <span class="d-none d-md-inline">{{ auth()->user()?->name ?? 'Admin' }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text">{{ __('admin.role') }}: {{ ucfirst(auth()->user()?->role ?? 'admin') }}</span></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.profile.show') }}">
                                <i class="bi bi-person me-2"></i>{{ __('admin.my_profile') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                                <i class="bi bi-pencil me-2"></i>{{ __('admin.edit_profile') }}
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right me-2"></i>{{ __('admin.logout') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Modern Sidebar Navigation -->
    <nav class="navbar navbar-expand-lg modern-sidebar fixed-top">
        <div class="container-fluid flex-column align-items-start p-0 h-100">
            <a class="navbar-brand w-100 text-center d-flex align-items-center justify-content-center gap-2" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('favicon_io/android-chrome-192x192.png') }}" alt="Global Heritage Logo" style="width:60px;height:60px;object-fit:contain">
                <span>Global Heritage</span>
            </a>

            <div class="nav-main w-100 flex-grow-1">
                <ul class="navbar-nav flex-column w-100">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i>{{ __('admin.dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-building me-2"></i>{{ __('admin.monuments') }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.monuments.index') }}">
                                    <i class="bi bi-list-ul me-2"></i>{{ __('admin.all_monuments') }}
                                </a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.monuments.create') }}">
                                    <i class="bi bi-plus-circle me-2"></i>{{ __('admin.create_new_monument') }}
                                </a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-file-text me-2"></i>{{ __('admin.posts') }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.posts.index') }}">
                                    <i class="bi bi-list-ul me-2"></i>{{ __('admin.all_posts') }}
                                </a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.posts.create') }}">
                                    <i class="bi bi-plus-circle me-2"></i>{{ __('admin.create_new_post') }}
                                </a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.gallery.index') }}">
                            <i class="bi bi-images me-2"></i>{{ __('admin.gallery') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.feedbacks.index') }}" onclick="markFeedbacksAsViewed()">
                            <i class="bi bi-chat-dots me-2"></i>{{ __('admin.feedbacks') }}
                            @php $unviewedFeedbacksCount = \App\Models\Feedback::where('status', 'approved')->unviewed()->count(); @endphp
                            @if($unviewedFeedbacksCount > 0)
                                <span id="feedback-badge" class="badge bg-danger ms-2">{{ $unviewedFeedbacksCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.contacts.index') }}">
                            <i class="bi bi-envelope me-2"></i>{{ __('admin.contactmessages') }}
                            @php $newContactsCount = \App\Models\Contact::where('status', 'new')->count(); @endphp
                            @if($newContactsCount > 0)
                            <span class="badge bg-danger ms-2">{{ $newContactsCount }}</span>
                            @endif
                        </a>
                    </li>
                    @if(auth()->user()?->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users.index') }}">
                            <i class="bi bi-people me-2"></i>{{ __('admin.users') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.visitors.index') }}">
                            <i class="bi bi-eye me-2"></i>{{ __('admin.visitorstats') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.settings.simple') }}">
                            <i class="bi bi-gear me-2"></i>{{ __('admin.settings') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.trash.index') }}">
                            <i class="bi bi-trash me-2"></i>{{ __('admin.trashmanagement') }}
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Notification Detail Modal -->
    <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel">
                        <i class="bi bi-bell me-2"></i>{{ __('admin.notification_details') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="notificationContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modern-secondary" data-bs-dismiss="modal">
                        {{ __('admin.close') }}
                    </button>
                    <button type="button" class="btn btn-modern-info" id="viewContentBtn" onclick="viewNotificationContent()" style="display:none;">
                        <i class="bi bi-eye me-1"></i>{{ __('admin.view_content') }}
                    </button>
                    <button type="button" class="btn btn-modern-primary" id="markAsReadBtn" onclick="markCurrentNotificationAsRead()">
                        <i class="bi bi-check me-1"></i>{{ __('admin.mark_as_read') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <main>
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Loading Component -->
    @include('components.loading')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- CKEditor 5 -->
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

    <!-- Scripts -->
    <script>
        // ===== Helpers cho badge trên chuông =====
        function getBellBadgeCount() {
            const el = document.querySelector('#notificationBell .badge');
            if (!el) return 0;
            const num = (el.textContent || '').replace(/[^\d]/g, ''); // lấy số, bỏ ký tự +
            return num ? parseInt(num, 10) : 0;
        }

        function setBellBadgeCount(count) {
            const btn = document.getElementById('notificationBell');
            if (!btn) return;
            let badge = btn.querySelector('.badge');

            if (count > 0) {
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                    btn.appendChild(badge);
                }
                badge.textContent = count > 99 ? '99+' : String(count);
            } else if (badge) {
                badge.remove();
            }
        }
        // Cập nhật badge
        function updateNotificationBadge() {
            fetch('/admin/notifications/unread-count', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    const badge = document.querySelector('#notificationBell .badge');
                    if (data.unread_count > 0) {
                        if (badge) {
                            badge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                        } else {
                            const button = document.querySelector('#notificationBell');
                            button.innerHTML = `<i class="bi bi-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        ${data.unread_count > 99 ? '99+' : data.unread_count}
                    </span>`;
                        }
                    } else {
                        if (badge) badge.remove();
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // ===== Mark as read một cái =====
        function markAsRead(notificationId) {
            fetch(`/admin/notifications/${notificationId}/mark-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    credentials: 'same-origin' // gửi cookie phiên
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Xử lý giao diện để cập nhật UI
                        const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
                        if (notificationItem) {
                            notificationItem.classList.remove('unread');
                            const dot = notificationItem.querySelector('.notification-dot');
                            if (dot) dot.remove();
                        }

                        // Cập nhật badge trên notification bell
                        updateNotificationBadge(); // Gọi hàm này để cập nhật số lượng thông báo chưa đọc
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // ===== Mark all as read =====
        // Sửa hàm markAllAsRead()
        function markAllAsRead() {
            fetch('/admin/notifications/mark-all-read', { // ✅ Sửa từ /api/ thành /admin/
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    credentials: 'same-origin'
                })
                .then(async res => res.status === 204 ? {
                    success: true
                } : res.json())
                .then(data => {
                    if (data.success !== false) {
                        document.querySelectorAll('.notification-item.unread').forEach(item => {
                            item.classList.remove('unread');
                            const dot = item.querySelector('.notification-dot');
                            if (dot) dot.remove();
                        });
                        setBellBadgeCount(0);
                        setTimeout(updateNotificationBadge, 500);
                    }
                })
                .catch(err => console.error('markAllAsRead error:', err));
        }

        // ===== Modal + các hàm còn lại của bạn giữ nguyên =====
        let currentNotificationId = null;

        function showNotificationDetail(notificationId) {
            currentNotificationId = notificationId;
            const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (!notificationItem) return;

            const title = notificationItem.querySelector('.notification-title').textContent;
            const message = notificationItem.getAttribute('data-full-message') || notificationItem.querySelector('.notification-message').textContent;
            const time = notificationItem.querySelector('.notification-time').textContent;
            const iconElement = notificationItem.querySelector('.notification-icon i');
            const isRead = !notificationItem.classList.contains('unread');
            const iconClass = iconElement ? iconElement.className : 'bi bi-bell text-primary';

            const currentLang = '{{ app()->getLocale() }}';
            let displayTitle = title;
            let displayMessage = message;
            if (title.includes(' / ')) {
                const parts = title.split(' / ');
                displayTitle = currentLang === 'vi' ? parts[0] : parts[1];
            }
            if (message.includes('\n\n')) {
                const parts = message.split('\n\n');
                displayMessage = currentLang === 'vi' ? parts[0] : parts[1];
            }

            const content = document.getElementById('notificationContent');
            content.innerHTML = `
      <div class="notification-detail">
        <div class="d-flex align-items-start mb-3">
          <div class="notification-icon-large me-3">
            <i class="${iconClass}" style="font-size: 2rem;"></i>
          </div>
          <div class="flex-grow-1">
            <h6 class="mb-1">${displayTitle}</h6>
            <small class="text-muted">${time}</small>
          </div>
        </div>
        <div class="notification-message-full"><p class="mb-0">${displayMessage}</p></div>
      </div>
    `;

            document.getElementById('markAsReadBtn').style.display = isRead ? 'none' : 'inline-block';

            const viewContentBtn = document.getElementById('viewContentBtn');
            const contentUrl = notificationItem.getAttribute('data-url');
            if (contentUrl && contentUrl !== '#') {
                viewContentBtn.style.display = 'inline-block';
                viewContentBtn.setAttribute('data-url', contentUrl);
            } else {
                viewContentBtn.style.display = 'none';
            }

            const modalElement = document.getElementById('notificationModal');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }

        function viewNotificationContent() {
            const url = document.getElementById('viewContentBtn').getAttribute('data-url');
            if (url && url !== '#') window.open(url, '_blank');
        }

        function markCurrentNotificationAsRead() {
            if (currentNotificationId) {
                markAsRead(currentNotificationId);
                const modal = bootstrap.Modal.getInstance(document.getElementById('notificationModal'));
                if (modal) modal.hide();
            }
        }

        // Event listener cho notification items
        document.addEventListener('click', function(e) {
            const notificationItem = e.target.closest('.notification-item');
            if (notificationItem && notificationItem.hasAttribute('data-notification-id')) {
                e.preventDefault();
                const notificationId = parseInt(notificationItem.getAttribute('data-notification-id'));
                showNotificationDetail(notificationId);
            }
        });

        // Expose cho onclick trong Blade
        window.showNotificationDetail = showNotificationDetail;
        window.markAsRead = markAsRead;
        window.markAllAsRead = markAllAsRead;
        window.viewNotificationContent = viewNotificationContent;
        window.markCurrentNotificationAsRead = markCurrentNotificationAsRead;

        // Tự refresh 30s/lần - TẠM TẮT ĐỂ TEST
        // setInterval(updateNotificationBadge, 30000);
    </script>

    <!-- Feedback Badge Script -->
    <script>
        function markFeedbacksAsViewed() {
            console.log('Marking feedbacks as viewed...');
            
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            console.log('CSRF Token:', csrfToken);
            
            // Mark all feedbacks as viewed
            fetch('/admin/feedbacks/mark-viewed', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                // Hide badge immediately
                const badge = document.getElementById('feedback-badge');
                if (badge) {
                    console.log('Hiding badge');
                    badge.style.display = 'none';
                } else {
                    console.log('Badge not found');
                }
            })
            .catch(error => {
                console.error('Error marking feedbacks as viewed:', error);
            });
        }
    </script>

    @stack('scripts')
</body>

</html>