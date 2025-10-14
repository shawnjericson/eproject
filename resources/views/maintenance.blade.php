<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ app()->getLocale() === 'vi' ? 'Bảo trì hệ thống' : 'System Maintenance' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3b82f6;
            --primary-dark: #2563eb;
            --secondary-color: #f8fafc;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
        }

        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .maintenance-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            max-width: 600px;
            width: 100%;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .maintenance-icon {
            font-size: 4rem;
            color: var(--warning-color);
            margin-bottom: 1.5rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .maintenance-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .maintenance-subtitle {
            font-size: 1.2rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .maintenance-features {
            background: var(--secondary-color);
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem 0;
            border: 1px solid var(--border-color);
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding: 0.75rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .feature-item:last-child {
            margin-bottom: 0;
        }

        .feature-icon {
            font-size: 1.5rem;
            color: var(--success-color);
            margin-right: 1rem;
            width: 40px;
            text-align: center;
        }

        .feature-text {
            color: var(--text-primary);
            font-weight: 500;
        }

        .maintenance-info {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin: 2rem 0;
        }

        .info-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .info-text {
            opacity: 0.9;
            line-height: 1.5;
        }

        .admin-login-btn {
            background: linear-gradient(135deg, var(--success-color), #059669);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .admin-login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
            color: white;
        }

        .language-switcher {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 0.5rem;
        }

        .lang-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .lang-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateY(-1px);
        }

        .lang-btn.active {
            background: rgba(255, 255, 255, 0.9);
            color: var(--text-primary);
        }

        @media (max-width: 768px) {
            .maintenance-container {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }
            
            .maintenance-title {
                font-size: 2rem;
            }
            
            .maintenance-subtitle {
                font-size: 1rem;
            }
            
            .language-switcher {
                position: relative;
                top: auto;
                right: auto;
                justify-content: center;
                margin-bottom: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Language Switcher -->
    <div class="language-switcher">
        <a href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}" 
           class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">
            <i class="bi bi-flag"></i> English
        </a>
        <a href="{{ request()->fullUrlWithQuery(['lang' => 'vi']) }}" 
           class="lang-btn {{ app()->getLocale() === 'vi' ? 'active' : '' }}">
            <i class="bi bi-flag"></i> Tiếng Việt
        </a>
    </div>

    <div class="maintenance-container">
        <!-- Maintenance Icon -->
        <div class="maintenance-icon">
            <i class="bi bi-tools"></i>
        </div>

        <!-- Vietnamese Content -->
        <div class="lang-content {{ app()->getLocale() === 'vi' ? 'active' : '' }}" data-lang="vi">
            <h1 class="maintenance-title">Bảo trì website</h1>
            <p class="maintenance-subtitle">
                Chúng tôi đang thực hiện bảo trì website để cải thiện trải nghiệm người dùng. 
                Vui lòng quay lại sau ít phút.
            </p>

            <div class="maintenance-features">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div class="feature-text">Bảo mật và độ tin cậy cao</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="bi bi-lightning"></i>
                    </div>
                    <div class="feature-text">Hiệu suất được tối ưu hóa</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="bi bi-heart"></i>
                    </div>
                    <div class="feature-text">Trải nghiệm người dùng tốt hơn</div>
                </div>
            </div>

            <div class="maintenance-info">
                <div class="info-title">Thông tin bảo trì</div>
                <div class="info-text">
                    Website sẽ hoạt động trở lại trong thời gian sớm nhất. 
                    Chúng tôi xin lỗi vì sự bất tiện này và cảm ơn sự kiên nhẫn của bạn.
                </div>
            </div>

            <a href="{{ route('login') }}" class="admin-login-btn">
                <i class="bi bi-person-gear"></i> Đăng nhập Admin
            </a>
        </div>

        <!-- English Content -->
        <div class="lang-content {{ app()->getLocale() === 'en' ? 'active' : '' }}" data-lang="en">
            <h1 class="maintenance-title">Website Maintenance</h1>
            <p class="maintenance-subtitle">
                We are currently performing website maintenance to improve your experience. 
                Please check back in a few minutes.
            </p>

            <div class="maintenance-features">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div class="feature-text">Enhanced Security & Reliability</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="bi bi-lightning"></i>
                    </div>
                    <div class="feature-text">Optimized Performance</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="bi bi-heart"></i>
                    </div>
                    <div class="feature-text">Better User Experience</div>
                </div>
            </div>

            <div class="maintenance-info">
                <div class="info-title">Maintenance Information</div>
                <div class="info-text">
                    The website will be back online shortly. 
                    We apologize for any inconvenience and thank you for your patience.
                </div>
            </div>

            <a href="{{ route('login') }}" class="admin-login-btn">
                <i class="bi bi-person-gear"></i> Admin Login
            </a>
        </div>
    </div>

    <!-- Language Switcher Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Language switching functionality
            const langButtons = document.querySelectorAll('.lang-btn');
            const langContents = document.querySelectorAll('.lang-content');

            langButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetLang = this.getAttribute('href').includes('lang=vi') ? 'vi' : 'en';
                    
                    // Update active language button
                    langButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Show/hide language content
                    langContents.forEach(content => {
                        if (content.dataset.lang === targetLang) {
                            content.classList.add('active');
                        } else {
                            content.classList.remove('active');
                        }
                    });
                    
                    // Update page language
                    document.documentElement.lang = targetLang;
                });
            });
        });
    </script>
</body>
</html>
