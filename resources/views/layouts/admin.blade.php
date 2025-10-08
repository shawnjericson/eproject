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

        /* Top Navigation Bar - Custom Modern Styling */
        .top-navbar {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
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
            margin: 0 auto;
        }

        .top-navbar-left {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .top-navbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-toggle {
            background: var(--primary-color);
            border: none;
            border-radius: 12px;
            padding: 0.75rem;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            font-size: 1.1rem;
        }

        .sidebar-toggle:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }

        .page-title {
            color: var(--text-primary);
            font-weight: 700;
            font-size: 1.4rem;
            margin: 0;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Custom Button Styles */
        .top-navbar .btn {
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        .top-navbar .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .top-navbar .btn:hover::before {
            left: 100%;
        }

        .top-navbar .btn-outline-secondary {
            background: white;
            color: var(--text-primary);
            border: 2px solid var(--border-color);
        }

        .top-navbar .btn-outline-secondary:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .top-navbar .btn-outline-primary {
            background: var(--primary-color);
            color: white;
            border: 2px solid var(--primary-color);
        }

        .top-navbar .btn-outline-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }

        /* Custom Dropdown Styles */
        .top-navbar .dropdown-menu {
            background: white;
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            padding: 0.5rem;
            margin-top: 0.5rem;
            min-width: 200px;
            backdrop-filter: blur(10px);
        }

        .top-navbar .dropdown-item {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            margin: 0.25rem 0;
            font-weight: 500;
            transition: all 0.2s ease;
            color: var(--text-primary);
        }

        .top-navbar .dropdown-item:hover {
            background: var(--primary-color);
            color: white;
            transform: translateX(4px);
        }

        .top-navbar .dropdown-item.active {
            background: var(--primary-color);
            color: white;
        }

        .top-navbar .dropdown-item-text {
            color: var(--text-secondary);
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
        }

        .top-navbar .dropdown-divider {
            margin: 0.5rem 0;
            border-color: var(--border-color);
        }

        /* User Profile Button */
        .top-navbar .user-profile-btn {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 50px;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .top-navbar .user-profile-btn:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .top-navbar .user-profile-btn img {
            width: 36px;
            height: 36px;
            border: 2px solid var(--primary-color);
        }

        /* Modern Sidebar */
        .modern-sidebar {
            background: #ffffff;
            border-right: 1px solid var(--border-color);
            box-shadow: none;
            width: var(--sidebar-width);
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            top: 70px; /* Adjust for top navbar */
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
            text-decoration: none;
        }

        .modern-sidebar .nav-link {
            color: var(--text-secondary) !important;
            padding: 0.75rem 1rem;
            margin: 0.25rem 0.5rem;
            border-radius: 6px;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-decoration: none;
        }

        .modern-sidebar .nav-link:hover {
            background: var(--secondary-color);
            color: var(--text-primary) !important;
        }

        .modern-sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white !important;
        }

        .modern-sidebar .dropdown-menu {
            background: #ffffff;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 8px;
            margin-left: 1rem;
            z-index: 1060;
        }

        .modern-sidebar .dropdown-item {
            color: var(--text-primary);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            margin: 0.25rem;
            font-weight: 500;
        }

        .modern-sidebar .dropdown-item:hover {
            background: var(--primary-color);
            color: white;
        }


        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: 70px; /* Account for top navbar */
            min-height: calc(100vh - 70px);
            padding: 2rem;
            overflow-x: hidden;
        }

        /* Modern Cards */
        .modern-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .modern-card:hover {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .modern-card .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .modern-card .card-body {
            padding: 1.5rem;
        }

        /* Modern Buttons */
        .btn-modern-primary {
            background: var(--text-primary);
            border: none;
            border-radius: 6px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            color: white;
            transition: all 0.2s ease;
        }

        .btn-modern-primary:hover {
            background: var(--primary-dark);
            color: white;
        }

        .btn-modern-secondary {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            color: var(--text-primary);
            transition: all 0.2s ease;
        }

        .btn-modern-secondary:hover {
            border-color: var(--text-primary);
            color: var(--text-primary);
        }

        /* Minimalist Action Buttons */
        .btn-minimal {
            background: transparent;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-secondary);
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-minimal:hover {
            border-color: var(--text-primary);
            color: var(--text-primary);
            background: var(--secondary-color);
        }

        .btn-minimal.btn-primary {
            color: var(--text-primary);
            border-color: var(--text-primary);
        }

        .btn-minimal.btn-primary:hover {
            background: var(--text-primary);
            color: white;
        }

        .btn-minimal.btn-success {
            color: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-minimal.btn-success:hover {
            background: var(--success-color);
            color: white;
        }

        .btn-minimal.btn-warning {
            color: var(--warning-color);
            border-color: var(--warning-color);
        }

        .btn-minimal.btn-warning:hover {
            background: var(--warning-color);
            color: white;
        }

        .btn-minimal.btn-danger {
            color: var(--danger-color);
            border-color: var(--danger-color);
        }

        .btn-minimal.btn-danger:hover {
            background: var(--danger-color);
            color: white;
        }

        /* Table improvements */
        .table-minimal {
            border: none;
        }

        .table-minimal th {
            border-bottom: 2px solid var(--border-color);
            font-weight: 600;
            color: var(--text-primary);
            background: transparent;
            padding: 1rem 0.75rem;
        }

        .table-minimal td {
            border-bottom: 1px solid var(--border-color);
            padding: 0.75rem;
            vertical-align: middle;
        }

        .table-minimal tbody tr:hover {
            background: var(--secondary-color);
        }

        /* Card improvements */
        .card-minimal {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-shadow: none;
            background: white;
        }

        .card-minimal .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .card-minimal .card-body {
            padding: 1.25rem;
        }

        /* Custom Pagination - Small and Clean */
        .pagination-sm .page-link {
            padding: 0.35rem 0.65rem;
            font-size: 0.8125rem;
            line-height: 1.3;
            border-radius: 4px;
            border: 1px solid #dee2e6;
            color: #374151;
            background-color: white;
            margin: 0 3px;
            min-width: 32px;
            text-align: center;
            font-weight: 500;
            transition: all 0.15s ease;
        }

        .pagination-sm .page-link:hover {
            background-color: #f3f4f6;
            border-color: #374151;
            color: #374151;
            text-decoration: none;
        }

        .pagination-sm .page-item.active .page-link {
            background-color: #374151;
            border-color: #374151;
            color: white;
            font-weight: 600;
        }

        .pagination-sm .page-item.disabled .page-link {
            color: #9ca3af;
            background-color: #f9fafb;
            border-color: #e5e7eb;
            opacity: 0.7;
            cursor: not-allowed;
        }

        .pagination-sm {
            margin: 0;
        }

        /* Professional Editor Styles */
        .editor-toolbar {
            background: var(--secondary-color);
            border: 1px solid var(--border-color);
            border-radius: 8px 8px 0 0;
            padding: 1rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .editor-section {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .editor-section-header {
            background: var(--secondary-color);
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            color: var(--text-primary);
            border-radius: 8px 8px 0 0;
        }

        .editor-section-body {
            padding: 1.25rem;
        }

        .image-gallery-item {
            position: relative;
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            transition: all 0.2s ease;
            margin-bottom: 1rem;
            cursor: pointer;
        }

        .image-gallery-item:hover {
            border-color: var(--text-primary);
            background: var(--secondary-color);
        }

        .image-gallery-item.drag-over {
            border-color: var(--success-color);
            border-style: solid;
            background: rgba(16, 185, 129, 0.1);
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .image-gallery-item.drag-over::before {
            content: "📁 Drop image here";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--success-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 500;
            z-index: 10;
        }

        .image-gallery-item.has-image {
            border-style: solid;
            border-color: var(--success-color);
            padding: 0;
        }

        .image-gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 6px;
        }

        .image-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 0.5rem;
            font-size: 0.875rem;
            border-radius: 0 0 6px 6px;
        }

        .toc-preview {
            background: var(--secondary-color);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .toc-preview h6 {
            margin-bottom: 0.75rem;
            color: var(--text-primary);
            font-weight: 600;
        }

        .toc-preview ul {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        .toc-preview li {
            padding: 0.25rem 0;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .toc-preview li.h2 {
            font-weight: 500;
            color: var(--text-primary);
        }

        .toc-preview li.h3 {
            padding-left: 1rem;
        }

        .toc-preview li.h4 {
            padding-left: 2rem;
        }

        /* Modern Form Controls */
        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        /* Page Header */
        .page-header {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .page-header h1 {
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-card.primary .stat-icon {
            background: var(--text-primary);
            color: white;
        }

        .stat-card.success .stat-icon {
            background: var(--success-color);
            color: white;
        }

        .stat-card.warning .stat-icon {
            background: var(--warning-color);
            color: white;
        }

        .stat-card.danger .stat-icon {
            background: var(--danger-color);
            color: white;
        }

        /* Clickable Cards */
        .clickable-card {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .clickable-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        .clickable-card:active {
            transform: translateY(-2px);
        }

        /* Navigation sections */
        .nav-main {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 1rem 0;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e0 #f7fafc;
        }

        /* Custom scrollbar for webkit browsers */
        .nav-main::-webkit-scrollbar {
            width: 6px;
        }

        .nav-main::-webkit-scrollbar-track {
            background: #f7fafc;
            border-radius: 3px;
        }

        .nav-main::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 3px;
        }

        .nav-main::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }

        .nav-bottom {
            flex-shrink: 0;
            border-top: 1px solid var(--border-color);
            padding: 1rem 0;
            background: #f8f9fa;
        }

        /* Sidebar improvements */
        .modern-sidebar .nav-item {
            margin-bottom: 0.25rem;
        }

        .modern-sidebar .nav-link {
            position: relative;
            transition: all 0.3s ease;
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
            transition: transform 0.3s ease;
        }

        .modern-sidebar .nav-link:hover::before,
        .modern-sidebar .nav-link.active::before {
            transform: scaleY(1);
        }

        .nav-bottom .nav-item {
            margin: 0.25rem 0.5rem;
        }

        .nav-bottom .nav-link {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            border-radius: 6px;
        }

        /* Dropdown improvements */
        .modern-sidebar .dropdown-item {
            color: var(--text-primary);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            margin: 0.25rem;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .modern-sidebar .dropdown-item:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Text truncation for long names */
        .text-truncate {
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .modern-sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 1000;
                height: 100vh;
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .text-truncate {
                max-width: 100px;
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
                        <li><hr class="dropdown-divider"></li>
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
                        <li><hr class="dropdown-divider"></li>
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
        <div class="container-fluid flex-column align-items-start p-0 h-80">
            <a class="navbar-brand w-100 text-center d-flex align-items-center justify-content-center gap-2" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('favicon_io/android-chrome-192x192.png') }}" alt="Global Heritage Logo" style="width: 60px; height: 60px; object-fit: contain;">
                <span>Global Heritage</span>
            </a>

            <!-- Main Navigation -->
            <div class="nav-main w-100">
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
                        <a class="nav-link" href="{{ route('admin.feedbacks.index') }}">
                            <i class="bi bi-chat-dots me-2"></i>{{ __('admin.feedbacks') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.contacts.index') }}">
                            <i class="bi bi-envelope me-2"></i>Contact Messages
                            @php
                                $newContactsCount = \App\Models\Contact::where('status', 'new')->count();
                            @endphp
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
                                <i class="bi bi-eye me-2"></i>Visitor Stats
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.settings.index') }}">
                                <i class="bi bi-gear me-2"></i>{{ __('admin.settings') }}
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

        </div>
    </nav>

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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- CKEditor 5 Rich Text Editor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

    <!-- Auto Filter Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-submit form when dropdown changes
            const autoFilterSelects = document.querySelectorAll('.auto-filter');
            autoFilterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    const form = this.closest('form');
                    if (form) {
                        form.submit();
                    }
                });
            });

            // Search input with debounce
            const searchInputs = document.querySelectorAll('input[name="search"]');
            searchInputs.forEach(input => {
                let timeout;
                input.addEventListener('input', function() {
                    clearTimeout(timeout);
                    const form = this.closest('form');
                    if (form) {
                        timeout = setTimeout(() => {
                            form.submit();
                        }, 500); // 500ms delay
                    }
                });
            });

        });
    </script>

    @stack('scripts')
</body>
</html>
