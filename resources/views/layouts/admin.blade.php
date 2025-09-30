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
            --sidebar-width: 280px;
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
        }

        /* Modern Sidebar */
        .modern-sidebar {
            background: #ffffff;
            border-right: 1px solid var(--border-color);
            box-shadow: none;
            width: var(--sidebar-width);
            height: 100vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
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
            min-height: 100vh;
            padding: 2rem;
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

        /* Navigation sections */
        .nav-main {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0;
        }

        .nav-bottom {
            flex-shrink: 0;
            border-top: 1px solid var(--border-color);
            padding: 1rem 0;
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
    <!-- Modern Sidebar Navigation -->
    <nav class="navbar navbar-expand-lg modern-sidebar fixed-top">
        <div class="container-fluid flex-column align-items-start p-0 h-100">
            <a class="navbar-brand w-100 text-center" href="{{ route('admin.dashboard') }}">
                Global Heritage
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
                    @if(auth()->user()?->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people me-2"></i>{{ __('admin.users') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.settings.index') }}">
                                <i class="bi bi-gear me-2"></i>{{ __('admin.settings') }}
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.profile.show') }}">
                            <i class="bi bi-person-circle me-2"></i>{{ __('admin.my_profile') }}
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Bottom Navigation -->
            <div class="nav-bottom w-100">
                <ul class="navbar-nav flex-column w-100">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            @if(app()->getLocale() == 'vi') {{ __('admin.vietnamese') }} @else {{ __('admin.english') }} @endif
                        </a>
                        <ul class="dropdown-menu">
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
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost:3000" target="_blank">
                            {{ __('admin.view_site') }}
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <img src="{{ auth()->user()?->avatar_url }}"
                                 alt="{{ auth()->user()?->name }}"
                                 class="rounded-circle me-2"
                                 style="width: 32px; height: 32px; object-fit: cover;">
                            {{ auth()->user()?->name ?? 'Admin' }}
                        </a>
                        <ul class="dropdown-menu">
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
                    </li>
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

    @stack('scripts')
</body>
</html>
