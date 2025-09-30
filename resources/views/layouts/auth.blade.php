<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('admin.login')) - {{ __('admin.global_heritage') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2c5f2d;
            --secondary-color: #97bc62;
            --accent-color: #d4a574;
            --dark-color: #1a1a1a;
            --light-color: #f8f9fa;
            --border-color: #e0e0e0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
            animation: backgroundMove 20s ease-in-out infinite;
        }

        @keyframes backgroundMove {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(50px, 50px); }
        }

        .auth-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 1100px;
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            display: flex;
            min-height: 600px;
        }

        /* Left Side - Branding */
        .auth-brand {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-color) 0%, #1a4d1b 100%);
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .auth-brand::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 30s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .brand-content {
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            backdrop-filter: blur(10px);
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        .brand-logo i {
            font-size: 60px;
            color: white;
        }

        .brand-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .brand-tagline {
            font-size: 1.1rem;
            opacity: 0.95;
            line-height: 1.6;
            max-width: 400px;
            margin: 0 auto;
        }

        .brand-features {
            margin-top: 40px;
            text-align: left;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            opacity: 0.9;
        }

        .feature-item i {
            font-size: 24px;
            margin-right: 15px;
            color: var(--accent-color);
        }

        /* Right Side - Form */
        .auth-form-container {
            flex: 1;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-header {
            margin-bottom: 40px;
        }

        .auth-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 10px;
        }

        .auth-subtitle {
            color: #6c757d;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: 500;
            color: var(--dark-color);
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .form-control {
            height: 50px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 12px 20px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(44, 95, 45, 0.1);
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            z-index: 10;
        }

        .input-group .form-control {
            padding-left: 50px;
        }

        .btn-primary {
            height: 50px;
            background: linear-gradient(135deg, var(--primary-color) 0%, #1a4d1b 100%);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(44, 95, 45, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(44, 95, 45, 0.4);
        }

        .form-check {
            margin-bottom: 20px;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .language-switcher {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 100;
        }

        .language-switcher .btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            backdrop-filter: blur(10px);
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .language-switcher .btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 25px;
        }

        @media (max-width: 768px) {
            .auth-container {
                flex-direction: column;
                max-width: 500px;
            }

            .auth-brand {
                padding: 40px 30px;
                min-height: 300px;
            }

            .brand-title {
                font-size: 2rem;
            }

            .brand-features {
                display: none;
            }

            .auth-form-container {
                padding: 40px 30px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Language Switcher -->
    <div class="language-switcher">
        <div class="dropdown">
            <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-globe"></i>
                @if(app()->getLocale() == 'vi') Tiếng Việt @else English @endif
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}"
                       href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}">
                        English
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ app()->getLocale() == 'vi' ? 'active' : '' }}"
                       href="{{ request()->fullUrlWithQuery(['lang' => 'vi']) }}">
                        Tiếng Việt
                    </a>
                </li>
            </ul>
        </div>
    </div>

    @yield('content')

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>

