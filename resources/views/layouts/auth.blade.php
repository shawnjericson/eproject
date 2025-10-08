<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('admin.login')) - {{ __('admin.global_heritage') }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Heroicons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/heroicons@2.0.18/24/outline/index.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9f4',
                            100: '#daf2e4',
                            200: '#b8e5cd',
                            300: '#88d1ad',
                            400: '#54b688',
                            500: '#2c9968',
                            600: '#1f7d53',
                            700: '#1a6444',
                            800: '#175038',
                            900: '#14422f',
                        },
                        accent: {
                            50: '#faf8f3',
                            100: '#f3ede0',
                            200: '#e7dbc1',
                            300: '#d4a574',
                            400: '#c89050',
                            500: '#b87a3c',
                            600: '#a66531',
                            700: '#8a4f2a',
                            800: '#714127',
                            900: '#5d3622',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        serif: ['Playfair Display', 'Georgia', 'serif'],
                    },
                }
            }
        }
    </script>

    <style>
        @keyframes backgroundMove {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(50px, 50px); }
        }

        .animated-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 50%, rgba(44, 95, 45, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(44, 95, 45, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(44, 95, 45, 0.03) 0%, transparent 50%);
            animation: backgroundMove 20s ease-in-out infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
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


    </style>

    @stack('styles')
</head>
<body class="min-h-screen bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center p-5 font-sans relative overflow-hidden animated-bg">
    <!-- Language Switcher -->
    <div class="absolute top-5 right-5 z-50">
        <div class="relative group">
            <button class="bg-white/20 backdrop-blur-md border border-white/30 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 hover:bg-white/30 flex items-center gap-2">
                @if(app()->getLocale() == 'vi')
                    🇻🇳 Tiếng Việt
                @else
                    🇬🇧 English
                @endif
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                <a href="{{ request()->fullUrlWithQuery(['lang' => 'vi']) }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-t-lg transition-colors {{ app()->getLocale() == 'vi' ? 'bg-primary-50 text-primary-700' : '' }}">
                    🇻🇳 <span>Tiếng Việt</span>
                </a>
                <a href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-b-lg transition-colors {{ app()->getLocale() == 'en' ? 'bg-primary-50 text-primary-700' : '' }}">
                    🇬🇧 <span>English</span>
                </a>
            </div>
        </div>
    </div>

    @yield('content')

    <!-- CSRF Token Refresh Script -->
    <script>
        // Refresh CSRF token every 30 minutes
        setInterval(function() {
            fetch('/csrf-token')
                .then(response => response.json())
                .then(data => {
                    document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);
                    // Update all CSRF input fields
                    document.querySelectorAll('input[name="_token"]').forEach(input => {
                        input.value = data.csrf_token;
                    });
                })
                .catch(error => console.log('CSRF token refresh failed:', error));
        }, 30 * 60 * 1000); // 30 minutes

        // Form submission with fresh CSRF token
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    // Get fresh CSRF token before submit
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const tokenInput = form.querySelector('input[name="_token"]');
                    if (tokenInput) {
                        tokenInput.value = csrfToken;
                    }
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>