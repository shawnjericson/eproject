# 🌍 Multilingual System - Developer Guide

## 📋 Overview

This guide explains how to use and maintain the multilingual system in the Global Heritage project.

---

## 🎯 Quick Start

### Adding a New Translatable Text

**1. Add to language files:**

```php
// resources/lang/en/admin.php
'my_new_text' => 'My New Text',

// resources/lang/vi/admin.php
'my_new_text' => 'Văn bản mới của tôi',
```

**2. Use in Blade templates:**

```blade
<h1>{{ __('admin.my_new_text') }}</h1>
```

**3. Use in JavaScript:**

```javascript
alert(window.__('my_new_text'));
```

---

## 📁 File Structure

```
resources/
├── lang/
│   ├── en/
│   │   └── admin.php          # English translations
│   └── vi/
│       └── admin.php          # Vietnamese translations
└── views/
    └── layouts/
        └── admin_js_translations.blade.php  # JS translations
```

---

## 🔑 Translation Keys Organization

### Naming Convention

```php
// Good ✅
'dashboard_subtitle' => '...',
'no_posts_yet' => '...',
'confirm_delete_feedback' => '...',
'js_loading_image' => '...',

// Bad ❌
'text1' => '...',
'msg' => '...',
'a' => '...',
```

### Key Prefixes

| Prefix | Usage | Example |
|--------|-------|---------|
| `js_` | JavaScript messages | `js_loading_image` |
| `confirm_` | Confirmation dialogs | `confirm_delete_post` |
| `no_` | Empty states | `no_posts_yet` |
| `placeholder_` | Form placeholders | `placeholder_title_en` |

### Module Organization

```php
return [
    // Navigation
    'dashboard' => '...',
    'posts' => '...',
    
    // Dashboard
    'welcome_back' => '...',
    'dashboard_subtitle' => '...',
    
    // Posts
    'create_post' => '...',
    'edit_post' => '...',
    
    // Monuments
    'create_monument' => '...',
    'edit_monument' => '...',
    
    // Gallery
    'upload_image' => '...',
    'image_title' => '...',
    
    // JavaScript
    'js_loading_image' => '...',
    'js_please_wait' => '...',
];
```

---

## 💻 Usage Examples

### 1. Blade Templates

**Simple text:**
```blade
<h1>{{ __('admin.dashboard') }}</h1>
<p>{{ __('admin.welcome_message') }}</p>
```

**With variables:**
```blade
<p>{{ __('admin.welcome_back') }}, {{ $user->name }}!</p>
```

**In attributes:**
```blade
<input type="text" placeholder="{{ __('admin.search') }}">
<button title="{{ __('admin.edit') }}">...</button>
```

**Alternative syntax:**
```blade
@lang('admin.dashboard')
```

### 2. Controllers

**Flash messages:**
```php
return redirect()->back()->with('success', __('admin.success_created'));
return redirect()->back()->with('error', __('admin.error_occurred'));
```

**Validation messages:**
```php
$request->validate([
    'title' => 'required|string|max:255',
], [
    'title.required' => __('admin.title') . ' ' . __('admin.required'),
]);
```

### 3. JavaScript

**Setup (already included in admin layout):**
```blade
@include('layouts.admin_js_translations')
```

**Usage:**
```javascript
// Simple translation
alert(window.__('loading'));
alert(window.__('please_wait'));

// With replacements
alert(window.__('file_too_large', {size: '10.5'}));

// Confirm dialogs
if (confirm(window.__('confirm_delete'))) {
    // Delete action
}
```

**Available translations:**
```javascript
window.translations = {
    loading: "Loading...",
    please_wait: "Please wait",
    error: "Error",
    success: "Success",
    confirm_delete: "Are you sure?",
    // ... and more
};
```

---

## 🌐 Language Switching

### Current Language

```php
// Get current locale
$locale = app()->getLocale(); // 'en' or 'vi'

// Set locale
app()->setLocale('vi');
```

### In Middleware

The `SetLocale` middleware automatically sets the language based on:
1. Session (`locale` key)
2. User preference (if logged in)
3. Default: `en`

### Switching Language

```php
// In controller
session(['locale' => 'vi']);
return redirect()->back();
```

```blade
{{-- In view --}}
<a href="{{ route('language.switch', 'vi') }}">Tiếng Việt</a>
<a href="{{ route('language.switch', 'en') }}">English</a>
```

---

## ✅ Best Practices

### 1. Always Use Translation Keys

**Bad ❌:**
```blade
<h1>Welcome to Admin Panel</h1>
<button>Save</button>
```

**Good ✅:**
```blade
<h1>{{ __('admin.welcome') }}</h1>
<button>{{ __('admin.save') }}</button>
```

### 2. Keep Keys Descriptive

**Bad ❌:**
```php
'msg1' => 'Post created successfully',
'txt' => 'Delete',
```

**Good ✅:**
```php
'success_created_post' => 'Post created successfully',
'delete' => 'Delete',
```

### 3. Organize by Module

```php
// Good ✅
return [
    // Dashboard
    'dashboard' => 'Dashboard',
    'dashboard_subtitle' => '...',
    
    // Posts
    'posts' => 'Posts',
    'create_post' => '...',
];
```

### 4. Use Consistent Naming

```php
// Good ✅
'create_post' => 'Create Post',
'create_monument' => 'Create Monument',
'create_user' => 'Create User',

// Bad ❌
'create_post' => 'Create Post',
'new_monument' => 'Create Monument',
'add_user' => 'Create User',
```

### 5. Add Comments

```php
return [
    // Navigation
    'dashboard' => 'Dashboard',
    'posts' => 'Posts',
    
    // Common Actions
    'create' => 'Create',
    'edit' => 'Edit',
    
    // JavaScript messages
    'js_loading' => 'Loading...',
];
```

---

## 🔧 Adding a New Language

### 1. Create Language Files

```bash
mkdir resources/lang/fr
cp resources/lang/en/admin.php resources/lang/fr/admin.php
```

### 2. Translate Content

```php
// resources/lang/fr/admin.php
return [
    'dashboard' => 'Tableau de bord',
    'posts' => 'Articles',
    // ... translate all keys
];
```

### 3. Update Middleware

```php
// app/Http/Middleware/SetLocale.php
$availableLocales = ['en', 'vi', 'fr']; // Add 'fr'
```

### 4. Update Language Switcher

```blade
<a href="{{ route('language.switch', 'fr') }}">Français</a>
```

---

## 🐛 Troubleshooting

### Issue: Translation not showing

**Check:**
1. Key exists in language file
2. Correct syntax: `{{ __('admin.key') }}`
3. File saved and cache cleared

**Solution:**
```bash
php artisan config:clear
php artisan cache:clear
```

### Issue: JavaScript translation not working

**Check:**
1. `admin_js_translations.blade.php` is included
2. Key exists in `window.translations`
3. Using correct syntax: `window.__('key')`

**Debug:**
```javascript
console.log(window.translations); // Check available translations
```

### Issue: Wrong language showing

**Check:**
1. Session locale: `session('locale')`
2. App locale: `app()->getLocale()`
3. Middleware is applied

**Solution:**
```php
// Force set locale
session(['locale' => 'vi']);
app()->setLocale('vi');
```

---

## 📊 Current Statistics

- **Total keys**: ~130+ keys
- **Languages**: 2 (English, Vietnamese)
- **Files updated**: 15+ views
- **JavaScript translations**: 20+ keys
- **Coverage**: ~95% of admin panel

---

## 🚀 Future Improvements

1. **Add more languages**: French, Spanish, Chinese
2. **Translation management UI**: Admin panel for managing translations
3. **Auto-translation**: Integration with Google Translate API
4. **Missing translation detection**: Script to find untranslated text
5. **Translation export/import**: CSV/Excel support

---

## 📝 Checklist for New Features

When adding new features, ensure:

- [ ] All text uses `__()` helper
- [ ] Keys added to both `en` and `vi` files
- [ ] JavaScript messages added to `admin_js_translations.blade.php`
- [ ] Tested with both languages
- [ ] No hardcoded text in views
- [ ] Validation messages translated
- [ ] Flash messages translated
- [ ] Alert/confirm dialogs translated

---

## 📚 Resources

- **Laravel Localization**: https://laravel.com/docs/localization
- **Translation Files**: `resources/lang/`
- **Middleware**: `app/Http/Middleware/SetLocale.php`
- **JS Translations**: `resources/views/layouts/admin_js_translations.blade.php`

---

**Last Updated**: 2025-09-30  
**Version**: 1.0  
**Maintainer**: Development Team

