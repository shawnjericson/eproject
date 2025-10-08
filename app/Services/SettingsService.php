<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    /**
     * Get a setting value by key with caching
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting.{$key}", 60, function () use ($key, $default) {
            return SiteSetting::get($key, $default);
        });
    }

    /**
     * Set a setting value by key
     */
    public static function set(string $key, $value): bool
    {
        $result = SiteSetting::set($key, $value);
        Cache::forget("setting.{$key}");
        return $result;
    }

    /**
     * Get all settings as an array
     */
    public static function all(): array
    {
        return Cache::remember('settings.all', 60, function () {
            return SiteSetting::all()->pluck('value', 'key')->toArray();
        });
    }

    /**
     * Get settings by category
     */
    public static function getByCategory(string $category): array
    {
        return Cache::remember("settings.category.{$category}", 60, function () use ($category) {
            return SiteSetting::where('category', $category)
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    /**
     * File Upload Settings
     */
    public static function getMaxImageSize(): int
    {
        return (int) self::get('max_image_size_mb', 5);
    }

    public static function getAllowedImageTypes(): array
    {
        $types = self::get('allowed_image_types', 'jpg,jpeg,png,gif,webp');
        return explode(',', $types);
    }

    public static function getMaxFilesPerPost(): int
    {
        return (int) self::get('max_files_per_post', 10);
    }

    public static function getMaxFilesPerMonument(): int
    {
        return (int) self::get('max_files_per_monument', 15);
    }

    public static function getImageCompressionQuality(): int
    {
        return (int) self::get('image_compression_quality', 85);
    }

    /**
     * User Management Settings
     */
    public static function canModeratorManageUsers(): bool
    {
        return self::get('moderator_can_manage_users', 'false') === 'true';
    }

    public static function canModeratorApprovePosts(): bool
    {
        return self::get('moderator_can_approve_posts', 'true') === 'true';
    }

    public static function canModeratorApproveMonuments(): bool
    {
        return self::get('moderator_can_approve_monuments', 'true') === 'true';
    }

    public static function canModeratorManageGallery(): bool
    {
        return self::get('moderator_can_manage_gallery', 'true') === 'true';
    }

    public static function canModeratorViewAnalytics(): bool
    {
        return self::get('moderator_can_view_analytics', 'false') === 'true';
    }

    public static function isUserRegistrationEnabled(): bool
    {
        return self::get('user_registration_enabled', 'true') === 'true';
    }

    public static function isAutoApproveRegistrations(): bool
    {
        return self::get('auto_approve_registrations', 'false') === 'true';
    }

    /**
     * Content Settings
     */
    public static function isPostApprovalRequired(): bool
    {
        return self::get('post_approval_required', 'true') === 'true';
    }

    public static function isMonumentApprovalRequired(): bool
    {
        return self::get('monument_approval_required', 'true') === 'true';
    }

    public static function getMaxPostContentLength(): int
    {
        return (int) self::get('max_post_content_length', 5000);
    }

    public static function getMaxMonumentDescriptionLength(): int
    {
        return (int) self::get('max_monument_description_length', 3000);
    }

    public static function isCommentsEnabled(): bool
    {
        return self::get('enable_comments', 'true') === 'true';
    }

    public static function isCommentApprovalRequired(): bool
    {
        return self::get('comment_approval_required', 'false') === 'true';
    }

    /**
     * System Settings
     */
    public static function isMaintenanceMode(): bool
    {
        return self::get('maintenance_mode', 'false') === 'true';
    }

    public static function getApiRateLimit(): int
    {
        return (int) self::get('api_rate_limit_per_minute', 60);
    }

    public static function getCacheDuration(): int
    {
        return (int) self::get('cache_duration_minutes', 60);
    }

    public static function isEmailNotificationsEnabled(): bool
    {
        return self::get('email_notifications_enabled', 'true') === 'true';
    }

    public static function getNotificationEmail(): string
    {
        return self::get('notification_email', 'admin@example.com');
    }

    /**
     * General Settings
     */
    public static function getSiteName(): string
    {
        return self::get('site_name', 'Global Heritage CMS');
    }

    public static function getSiteDescription(): string
    {
        return self::get('site_description', 'A comprehensive content management system for heritage sites');
    }

    public static function getContactEmail(): string
    {
        return self::get('contact_email', 'contact@example.com');
    }

    public static function getContactPhone(): string
    {
        return self::get('contact_phone', '+1 (555) 123-4567');
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache(): void
    {
        Cache::forget('settings.all');
        
        // Clear individual setting caches
        $settings = SiteSetting::all();
        foreach ($settings as $setting) {
            Cache::forget("setting.{$setting->key}");
        }
        
        // Clear category caches
        $categories = $settings->pluck('category')->unique()->filter();
        foreach ($categories as $category) {
            Cache::forget("settings.category.{$category}");
        }
    }

    /**
     * Get setting with validation
     */
    public static function getValidated(string $key, $default = null, string $type = 'string')
    {
        $value = self::get($key, $default);
        
        switch ($type) {
            case 'boolean':
                return $value === 'true';
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'array':
                return is_string($value) ? explode(',', $value) : $value;
            default:
                return $value;
        }
    }
}
