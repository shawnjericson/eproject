<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class AdminSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // File Upload Settings
            [
                'key' => 'max_image_size_mb',
                'value' => '5',
                'description' => 'Maximum image file size in MB',
                'category' => 'file_upload',
                'type' => 'number',
                'min' => '1',
                'max' => '50'
            ],
            [
                'key' => 'allowed_image_types',
                'value' => 'jpg,jpeg,png,gif,webp',
                'description' => 'Allowed image file types (comma-separated)',
                'category' => 'file_upload',
                'type' => 'text'
            ],
            [
                'key' => 'max_files_per_post',
                'value' => '10',
                'description' => 'Maximum number of images per post',
                'category' => 'file_upload',
                'type' => 'number',
                'min' => '1',
                'max' => '50'
            ],
            [
                'key' => 'max_files_per_monument',
                'value' => '15',
                'description' => 'Maximum number of images per monument',
                'category' => 'file_upload',
                'type' => 'number',
                'min' => '1',
                'max' => '100'
            ],
            [
                'key' => 'image_compression_quality',
                'value' => '85',
                'description' => 'Image compression quality (1-100)',
                'category' => 'file_upload',
                'type' => 'number',
                'min' => '1',
                'max' => '100'
            ],

            // User Management Settings
            [
                'key' => 'moderator_can_manage_users',
                'value' => 'false',
                'description' => 'Allow moderators to manage users',
                'category' => 'user_management',
                'type' => 'boolean'
            ],
            [
                'key' => 'moderator_can_approve_posts',
                'value' => 'true',
                'description' => 'Allow moderators to approve posts',
                'category' => 'user_management',
                'type' => 'boolean'
            ],
            [
                'key' => 'moderator_can_approve_monuments',
                'value' => 'true',
                'description' => 'Allow moderators to approve monuments',
                'category' => 'user_management',
                'type' => 'boolean'
            ],
            [
                'key' => 'moderator_can_manage_gallery',
                'value' => 'true',
                'description' => 'Allow moderators to manage gallery',
                'category' => 'user_management',
                'type' => 'boolean'
            ],
            [
                'key' => 'moderator_can_view_analytics',
                'value' => 'false',
                'description' => 'Allow moderators to view analytics',
                'category' => 'user_management',
                'type' => 'boolean'
            ],
            [
                'key' => 'user_registration_enabled',
                'value' => 'true',
                'description' => 'Enable user registration',
                'category' => 'user_management',
                'type' => 'boolean'
            ],
            [
                'key' => 'auto_approve_registrations',
                'value' => 'false',
                'description' => 'Automatically approve new user registrations',
                'category' => 'user_management',
                'type' => 'boolean'
            ],

            // Content Settings
            [
                'key' => 'post_approval_required',
                'value' => 'true',
                'description' => 'Require admin approval for posts',
                'category' => 'content',
                'type' => 'boolean'
            ],
            [
                'key' => 'monument_approval_required',
                'value' => 'true',
                'description' => 'Require admin approval for monuments',
                'category' => 'content',
                'type' => 'boolean'
            ],
            [
                'key' => 'max_post_content_length',
                'value' => '5000',
                'description' => 'Maximum post content length (characters)',
                'category' => 'content',
                'type' => 'number',
                'min' => '100',
                'max' => '50000'
            ],
            [
                'key' => 'max_monument_description_length',
                'value' => '3000',
                'description' => 'Maximum monument description length (characters)',
                'category' => 'content',
                'type' => 'number',
                'min' => '100',
                'max' => '20000'
            ],
            [
                'key' => 'enable_comments',
                'value' => 'true',
                'description' => 'Enable comments on posts and monuments',
                'category' => 'content',
                'type' => 'boolean'
            ],
            [
                'key' => 'comment_approval_required',
                'value' => 'false',
                'description' => 'Require approval for comments',
                'category' => 'content',
                'type' => 'boolean'
            ],

            // System Settings
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'description' => 'Enable maintenance mode',
                'category' => 'system',
                'type' => 'boolean'
            ],
            [
                'key' => 'api_rate_limit_per_minute',
                'value' => '60',
                'description' => 'API rate limit per minute',
                'category' => 'system',
                'type' => 'number',
                'min' => '10',
                'max' => '1000'
            ],
            [
                'key' => 'cache_duration_minutes',
                'value' => '60',
                'description' => 'Cache duration in minutes',
                'category' => 'system',
                'type' => 'number',
                'min' => '5',
                'max' => '1440'
            ],
            [
                'key' => 'email_notifications_enabled',
                'value' => 'true',
                'description' => 'Enable email notifications',
                'category' => 'system',
                'type' => 'boolean'
            ],
            [
                'key' => 'notification_email',
                'value' => 'admin@example.com',
                'description' => 'Email address for notifications',
                'category' => 'system',
                'type' => 'email'
            ],

            // General Settings
            [
                'key' => 'site_name',
                'value' => 'Global Heritage CMS',
                'description' => 'Site name',
                'category' => 'general',
                'type' => 'text'
            ],
            [
                'key' => 'site_description',
                'value' => 'A comprehensive content management system for heritage sites',
                'description' => 'Site description',
                'category' => 'general',
                'type' => 'textarea'
            ],
            [
                'key' => 'contact_email',
                'value' => 'contact@example.com',
                'description' => 'Contact email address',
                'category' => 'general',
                'type' => 'email'
            ],
            [
                'key' => 'contact_phone',
                'value' => '+1 (555) 123-4567',
                'description' => 'Contact phone number',
                'category' => 'general',
                'type' => 'text'
            ]
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}