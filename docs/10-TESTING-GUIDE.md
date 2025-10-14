# **🧪 Testing Guide - Global Heritage Project**

## **📋 Overview**

Hướng dẫn testing toàn diện cho dự án Global Heritage, bao gồm unit tests, integration tests, và end-to-end tests.

---

## **🎯 Testing Strategy**

### **Testing Pyramid**
```
    /\
   /  \
  /E2E \     ← End-to-End Tests (10%)
 /______\
/        \
/Integration\ ← Integration Tests (20%)
/____________\
/              \
/   Unit Tests   \ ← Unit Tests (70%)
/________________\
```

### **Testing Types**
- **Unit Tests**: Test individual components/functions
- **Integration Tests**: Test component interactions
- **Feature Tests**: Test complete features
- **Browser Tests**: Test user interactions
- **API Tests**: Test API endpoints
- **Performance Tests**: Test system performance

---

## **🔧 Backend Testing (Laravel)**

### **1. Setup Testing Environment**

#### **Install Testing Dependencies**
```bash
# Install PHPUnit (already included in Laravel)
composer install --dev

# Install Laravel Dusk for browser testing
composer require --dev laravel/dusk
php artisan dusk:install
```

#### **Configure Testing Database**
```bash
# Create testing database
mysql -u root -p
CREATE DATABASE global_heritage_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON global_heritage_test.* TO 'heritage_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### **Update .env.testing**
```env
APP_ENV=testing
APP_DEBUG=true
APP_KEY=base64:your_test_key_here

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=global_heritage_test
DB_USERNAME=heritage_user
DB_PASSWORD=strong_password_here

CACHE_DRIVER=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync

MAIL_MAILER=array
```

### **2. Unit Tests**

#### **Model Tests**
```php
// tests/Unit/Models/UserTest.php
<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'admin'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'admin'
        ]);
    }

    public function test_user_has_posts_relationship()
    {
        $user = User::factory()->create();
        $post = $user->posts()->create([
            'title' => 'Test Post',
            'content' => 'Test content',
            'status' => 'draft'
        ]);

        $this->assertTrue($user->posts->contains($post));
    }

    public function test_user_is_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $moderator = User::factory()->create(['role' => 'moderator']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($moderator->isAdmin());
    }
}
```

#### **Service Tests**
```php
// tests/Unit/Services/SettingsServiceTest.php
<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\SettingsService;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SettingsServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_site_name()
    {
        SiteSetting::create([
            'key' => 'site_name',
            'value' => 'Test Site'
        ]);

        $siteName = SettingsService::get('site_name');

        $this->assertEquals('Test Site', $siteName);
    }

    public function test_get_boolean_setting()
    {
        SiteSetting::create([
            'key' => 'user_registration_enabled',
            'value' => 'true'
        ]);

        $isEnabled = SettingsService::getBoolean('user_registration_enabled');

        $this->assertTrue($isEnabled);
    }

    public function test_set_setting()
    {
        SettingsService::set('site_name', 'New Site Name');

        $this->assertDatabaseHas('site_settings', [
            'key' => 'site_name',
            'value' => 'New Site Name'
        ]);
    }
}
```

### **3. Feature Tests**

#### **API Tests**
```php
// tests/Feature/Api/MonumentApiTest.php
<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Monument;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MonumentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_monuments_list()
    {
        Monument::factory()->count(3)->create();

        $response = $this->getJson('/api/monuments');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'items' => [
                            '*' => [
                                'id',
                                'name',
                                'description',
                                'location',
                                'images'
                            ]
                        ],
                        'pagination'
                    ]
                ]);
    }

    public function test_can_get_monument_detail()
    {
        $monument = Monument::factory()->create();

        $response = $this->getJson("/api/monuments/{$monument->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'id' => $monument->id,
                        'name' => $monument->name
                    ]
                ]);
    }

    public function test_can_create_monument_as_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin, 'sanctum');

        $monumentData = [
            'name' => 'Test Monument',
            'description' => 'Test description',
            'location' => 'Test Location',
            'latitude' => 13.4125,
            'longitude' => 103.8670,
            'zone' => 'southeast',
            'is_wonder' => false
        ];

        $response = $this->postJson('/api/monuments', $monumentData);

        $response->assertStatus(201)
                ->assertJson([
                    'success' => true,
                    'message' => 'Monument created successfully'
                ]);

        $this->assertDatabaseHas('monuments', [
            'name' => 'Test Monument'
        ]);
    }

    public function test_cannot_create_monument_as_guest()
    {
        $monumentData = [
            'name' => 'Test Monument',
            'description' => 'Test description'
        ];

        $response = $this->postJson('/api/monuments', $monumentData);

        $response->assertStatus(401);
    }
}
```

#### **Web Tests**
```php
// tests/Feature/Web/AdminMonumentTest.php
<?php

namespace Tests\Feature\Web;

use Tests\TestCase;
use App\Models\User;
use App\Models\Monument;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminMonumentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_monuments_index()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)
                        ->get('/admin/monuments');

        $response->assertStatus(200)
                ->assertViewIs('admin.monuments.index');
    }

    public function test_moderator_cannot_view_other_users_monuments()
    {
        $moderator = User::factory()->create(['role' => 'moderator']);
        $otherUser = User::factory()->create();
        
        Monument::factory()->create(['created_by' => $otherUser->id]);

        $response = $this->actingAs($moderator)
                        ->get('/admin/monuments');

        $response->assertStatus(200);
        // Moderator should only see their own monuments
        $this->assertCount(0, $response->viewData('monuments'));
    }

    public function test_can_create_monument_via_form()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $monumentData = [
            'name' => 'Test Monument',
            'description' => 'Test description',
            'location' => 'Test Location',
            'latitude' => 13.4125,
            'longitude' => 103.8670,
            'zone' => 'southeast',
            'is_wonder' => false
        ];

        $response = $this->actingAs($admin)
                        ->post('/admin/monuments', $monumentData);

        $response->assertRedirect('/admin/monuments')
                ->assertSessionHas('success');

        $this->assertDatabaseHas('monuments', [
            'name' => 'Test Monument'
        ]);
    }
}
```

### **4. Browser Tests (Laravel Dusk)**

#### **Setup Browser Testing**
```php
// tests/Browser/AdminLoginTest.php
<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminLoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_admin_can_login()
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin'
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->type('email', 'admin@example.com')
                    ->type('password', 'password123')
                    ->press('Login')
                    ->assertPathIs('/admin/dashboard')
                    ->assertSee('Dashboard');
        });
    }

    public function test_moderator_cannot_access_admin_users()
    {
        $moderator = User::factory()->create([
            'email' => 'moderator@example.com',
            'password' => bcrypt('password123'),
            'role' => 'moderator'
        ]);

        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('email', 'moderator@example.com')->first())
                    ->visit('/admin/users')
                    ->assertSee('403')
                    ->assertSee('Forbidden');
        });
    }
}
```

#### **Frontend Integration Tests**
```php
// tests/Browser/FrontendMonumentTest.php
<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Models\Monument;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FrontendMonumentTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_user_can_view_monuments_list()
    {
        Monument::factory()->count(5)->create();

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Monuments')
                    ->clickLink('Monuments')
                    ->assertPathIs('/monuments')
                    ->assertSee('Monument')
                    ->assertVisible('.monument-card');
        });
    }

    public function test_user_can_view_monument_detail()
    {
        $monument = Monument::factory()->create([
            'name' => 'Test Monument',
            'description' => 'Test description'
        ]);

        $this->browse(function (Browser $browser) use ($monument) {
            $browser->visit("/monuments/{$monument->id}")
                    ->assertSee('Test Monument')
                    ->assertSee('Test description')
                    ->assertVisible('.monument-gallery');
        });
    }

    public function test_user_can_submit_feedback()
    {
        $monument = Monument::factory()->create();

        $this->browse(function (Browser $browser) use ($monument) {
            $browser->visit("/monuments/{$monument->id}")
                    ->scrollTo('.feedback-form')
                    ->type('user_name', 'John Doe')
                    ->type('user_email', 'john@example.com')
                    ->select('rating', '5')
                    ->type('comment', 'Great monument!')
                    ->press('Submit Feedback')
                    ->assertSee('Feedback submitted successfully');
        });
    }
}
```

---

## **⚛️ Frontend Testing (React)**

### **1. Setup Testing Environment**

#### **Install Testing Dependencies**
```bash
cd frontend
npm install --save-dev @testing-library/react @testing-library/jest-dom @testing-library/user-event jest-environment-jsdom
```

#### **Configure Jest**
```javascript
// frontend/jest.config.js
module.exports = {
  testEnvironment: 'jsdom',
  setupFilesAfterEnv: ['<rootDir>/src/setupTests.js'],
  moduleNameMapping: {
    '\\.(css|less|scss|sass)$': 'identity-obj-proxy',
  },
  transform: {
    '^.+\\.(js|jsx)$': 'babel-jest',
  },
  testMatch: [
    '<rootDir>/src/**/__tests__/**/*.(js|jsx)',
    '<rootDir>/src/**/*.(test|spec).(js|jsx)'
  ],
  collectCoverageFrom: [
    'src/**/*.(js|jsx)',
    '!src/index.js',
    '!src/setupTests.js'
  ]
};
```

#### **Setup Test Environment**
```javascript
// frontend/src/setupTests.js
import '@testing-library/jest-dom';
```

### **2. Component Tests**

#### **MonumentCard Test**
```javascript
// frontend/src/components/__tests__/MonumentCard.test.js
import React from 'react';
import { render, screen } from '@testing-library/react';
import { BrowserRouter } from 'react-router-dom';
import MonumentCard from '../MonumentCard';

const mockMonument = {
  id: 1,
  name: 'Test Monument',
  description: 'Test description',
  location: 'Test Location',
  images: [
    {
      id: 1,
      url: 'https://example.com/image.jpg',
      alt: 'Test image',
      is_primary: true
    }
  ]
};

const renderWithRouter = (component) => {
  return render(
    <BrowserRouter>
      {component}
    </BrowserRouter>
  );
};

describe('MonumentCard', () => {
  test('renders monument information', () => {
    renderWithRouter(<MonumentCard monument={mockMonument} />);
    
    expect(screen.getByText('Test Monument')).toBeInTheDocument();
    expect(screen.getByText('Test description')).toBeInTheDocument();
    expect(screen.getByText('Test Location')).toBeInTheDocument();
  });

  test('renders primary image', () => {
    renderWithRouter(<MonumentCard monument={mockMonument} />);
    
    const image = screen.getByAltText('Test image');
    expect(image).toBeInTheDocument();
    expect(image).toHaveAttribute('src', 'https://example.com/image.jpg');
  });

  test('has link to monument detail', () => {
    renderWithRouter(<MonumentCard monument={mockMonument} />);
    
    const link = screen.getByRole('link');
    expect(link).toHaveAttribute('href', '/monuments/1');
  });
});
```

#### **LanguageContext Test**
```javascript
// frontend/src/contexts/__tests__/LanguageContext.test.js
import React from 'react';
import { render, screen, act } from '@testing-library/react';
import { LanguageProvider } from '../LanguageContext';
import LanguageSwitcher from '../../components/LanguageSwitcher';

const TestComponent = () => {
  return (
    <div>
      <h1 data-testid="title">Test Title</h1>
      <LanguageSwitcher />
    </div>
  );
};

describe('LanguageContext', () => {
  test('provides default language', () => {
    render(
      <LanguageProvider>
        <TestComponent />
      </LanguageProvider>
    );
    
    expect(screen.getByTestId('title')).toBeInTheDocument();
  });

  test('allows language switching', () => {
    render(
      <LanguageProvider>
        <TestComponent />
      </LanguageProvider>
    );
    
    const vietnameseButton = screen.getByText('Tiếng Việt');
    const englishButton = screen.getByText('English');
    
    expect(vietnameseButton).toBeInTheDocument();
    expect(englishButton).toBeInTheDocument();
  });
});
```

### **3. API Service Tests**

#### **API Service Test**
```javascript
// frontend/src/services/__tests__/api.test.js
import { apiService } from '../api';

// Mock fetch
global.fetch = jest.fn();

describe('API Service', () => {
  beforeEach(() => {
    fetch.mockClear();
  });

  test('fetches monuments successfully', async () => {
    const mockMonuments = [
      { id: 1, name: 'Monument 1' },
      { id: 2, name: 'Monument 2' }
    ];

    fetch.mockResolvedValueOnce({
      ok: true,
      json: async () => ({
        success: true,
        data: { items: mockMonuments }
      })
    });

    const result = await apiService.getMonuments();

    expect(fetch).toHaveBeenCalledWith('/api/monuments');
    expect(result.success).toBe(true);
    expect(result.data.items).toEqual(mockMonuments);
  });

  test('handles API errors', async () => {
    fetch.mockRejectedValueOnce(new Error('Network error'));

    await expect(apiService.getMonuments()).rejects.toThrow('Network error');
  });

  test('submits feedback successfully', async () => {
    const feedbackData = {
      rating: 5,
      comment: 'Great!',
      user_name: 'John Doe',
      user_email: 'john@example.com',
      monument_id: 1
    };

    fetch.mockResolvedValueOnce({
      ok: true,
      json: async () => ({
        success: true,
        message: 'Feedback submitted successfully'
      })
    });

    const result = await apiService.submitFeedback(feedbackData);

    expect(fetch).toHaveBeenCalledWith('/api/feedbacks', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(feedbackData)
    });
    expect(result.success).toBe(true);
  });
});
```

---

## **🔧 Test Configuration**

### **1. PHPUnit Configuration**
```xml
<!-- phpunit.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="./vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true">
    <testsuites>
        <testsuite name="Unit">
            <directory suffix="Test.php">./tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory suffix="Test.php">./tests/Feature</directory>
        </testsuite>
        <testsuite name="Browser">
            <directory suffix="Test.php">./tests/Browser</directory>
        </testsuite>
    </testsuites>
    <coverage processUncoveredFiles="true">
        <include>
            <directory suffix=".php">./app</directory>
        </include>
        <exclude>
            <directory>./app/Console</directory>
            <directory>./app/Exceptions</directory>
        </exclude>
    </coverage>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="BCRYPT_ROUNDS" value="4"/>
        <env name="CACHE_DRIVER" value="array"/>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
        <env name="MAIL_MAILER" value="array"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
        <env name="SESSION_DRIVER" value="array"/>
    </php>
</phpunit>
```

### **2. Dusk Configuration**
```php
// tests/DuskTestCase.php
<?php

namespace Tests;

use Laravel\Dusk\TestCase as BaseTestCase;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
            '--headless',
            '--no-sandbox',
            '--disable-dev-shm-usage',
        ]);

        return RemoteWebDriver::create(
            'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY,
                $options
            )
        );
    }
}
```

---

## **📊 Test Execution**

### **1. Run All Tests**
```bash
# Backend tests
php artisan test

# Frontend tests
cd frontend && npm test

# Browser tests
php artisan dusk
```

### **2. Run Specific Test Suites**
```bash
# Unit tests only
php artisan test --testsuite=Unit

# Feature tests only
php artisan test --testsuite=Feature

# Specific test file
php artisan test tests/Unit/Models/UserTest.php

# Frontend specific tests
cd frontend && npm test -- --testPathPattern=MonumentCard
```

### **3. Generate Coverage Reports**
```bash
# Backend coverage
php artisan test --coverage

# Frontend coverage
cd frontend && npm test -- --coverage
```

---

## **🚀 CI/CD Integration**

### **1. GitHub Actions Workflow**
```yaml
# .github/workflows/tests.yml
name: Tests

on:
  push:
    branches: [ main, develop ]
  pull_request:
    branches: [ main ]

jobs:
  backend-tests:
    runs-on: ubuntu-latest
    
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ROOT_PASSWORD: password
          MYSQL_DATABASE: global_heritage_test
        ports:
          - 3306:3306
        options: --health-cmd="mysqladmin ping" --health-interval=10s --health-timeout=5s --health-retries=3

    steps:
    - uses: actions/checkout@v2
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.1'
        extensions: mbstring, dom, fileinfo, mysql, redis, gd
        coverage: xdebug
    
    - name: Install dependencies
      run: composer install --no-progress --prefer-dist --optimize-autoloader
    
    - name: Copy .env
      run: php -r "file_exists('.env') || copy('.env.example', '.env');"
    
    - name: Generate key
      run: php artisan key:generate
    
    - name: Run migrations
      run: php artisan migrate --env=testing
    
    - name: Run tests
      run: php artisan test --coverage

  frontend-tests:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v2
    
    - name: Setup Node.js
      uses: actions/setup-node@v2
      with:
        node-version: '18'
        cache: 'npm'
        cache-dependency-path: frontend/package-lock.json
    
    - name: Install dependencies
      run: |
        cd frontend
        npm ci
    
    - name: Run tests
      run: |
        cd frontend
        npm test -- --coverage --watchAll=false
```

### **2. Test Database Setup**
```bash
# Create test database
mysql -u root -p
CREATE DATABASE global_heritage_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON global_heritage_test.* TO 'heritage_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## **📈 Performance Testing**

### **1. Load Testing with Artillery**
```yaml
# artillery-config.yml
config:
  target: 'http://localhost:8000'
  phases:
    - duration: 60
      arrivalRate: 10
    - duration: 120
      arrivalRate: 20
    - duration: 60
      arrivalRate: 10

scenarios:
  - name: "Monument API Load Test"
    weight: 70
    flow:
      - get:
          url: "/api/monuments"
      - get:
          url: "/api/monuments/1"
  
  - name: "Feedback Submission Load Test"
    weight: 30
    flow:
      - post:
          url: "/api/feedbacks"
          json:
            rating: 5
            comment: "Test feedback"
            user_name: "Test User"
            user_email: "test@example.com"
            monument_id: 1
```

### **2. Run Performance Tests**
```bash
# Install Artillery
npm install -g artillery

# Run load test
artillery run artillery-config.yml

# Run with report
artillery run artillery-config.yml --output report.json
artillery report report.json
```

---

## **🔍 Debugging Tests**

### **1. Debug PHP Tests**
```bash
# Run with verbose output
php artisan test --verbose

# Run specific test with debug
php artisan test tests/Feature/Api/MonumentApiTest.php --verbose

# Debug with Xdebug
php -d xdebug.mode=debug php artisan test
```

### **2. Debug Frontend Tests**
```bash
# Run with debug output
cd frontend && npm test -- --verbose

# Run specific test
cd frontend && npm test -- MonumentCard.test.js

# Run with watch mode
cd frontend && npm test -- --watch
```

### **3. Debug Browser Tests**
```bash
# Run without headless mode
php artisan dusk --browser=chrome

# Run specific browser test
php artisan dusk tests/Browser/AdminLoginTest.php
```

---

## **📊 Test Metrics**

### **1. Coverage Targets**
- **Unit Tests**: 90%+ coverage
- **Feature Tests**: 80%+ coverage
- **API Tests**: 95%+ coverage
- **Frontend Tests**: 85%+ coverage

### **2. Performance Targets**
- **API Response Time**: < 200ms
- **Page Load Time**: < 2s
- **Database Query Time**: < 100ms
- **Memory Usage**: < 256MB

### **3. Quality Gates**
- All tests must pass
- Coverage must meet targets
- No critical security vulnerabilities
- Performance within targets
- Code quality score > 8.0

---

**🧪 Testing Guide này cung cấp framework toàn diện để đảm bảo chất lượng và độ tin cậy của dự án Global Heritage thông qua testing ở mọi cấp độ.**


