<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Monument;
use Illuminate\Support\Facades\Hash;

class PermissionTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Tạo test data để kiểm tra phân quyền
     */
    public function run(): void
    {
        // Tạo test users nếu chưa có
        $admin = User::firstOrCreate(
            ['email' => 'test-admin@example.com'],
            [
                'name' => 'Test Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        $moderator = User::firstOrCreate(
            ['email' => 'test-moderator@example.com'],
            [
                'name' => 'Test Moderator',
                'password' => Hash::make('password123'),
                'role' => 'moderator',
                'status' => 'active',
            ]
        );

        // Tạo test posts với các status khác nhau
        $pendingPost = Post::firstOrCreate(
            ['title' => 'Test Pending Post'],
            [
                'content' => 'This is a test pending post for permission testing.',
                'status' => 'pending',
                'created_by' => $moderator->id,
            ]
        );

        $approvedPost = Post::firstOrCreate(
            ['title' => 'Test Approved Post'],
            [
                'content' => 'This is a test approved post for permission testing.',
                'status' => 'approved',
                'published_at' => now(),
                'created_by' => $moderator->id,
            ]
        );

        // Tạo test monuments với các status khác nhau
        $pendingMonument = Monument::firstOrCreate(
            ['title' => 'Test Pending Monument'],
            [
                'description' => 'This is a test pending monument for permission testing.',
                'history' => 'Test history content.',
                'zone' => 'Central',
                'status' => 'pending',
                'created_by' => $moderator->id,
            ]
        );

        $approvedMonument = Monument::firstOrCreate(
            ['title' => 'Test Approved Monument'],
            [
                'description' => 'This is a test approved monument for permission testing.',
                'history' => 'Test history content.',
                'zone' => 'Central',
                'status' => 'approved',
                'created_by' => $moderator->id,
            ]
        );

        $this->command->info('Test data created successfully!');
        $this->command->info('Admin user: test-admin@example.com / password123');
        $this->command->info('Moderator user: test-moderator@example.com / password123');
        $this->command->info('Created posts: 1 pending, 1 approved');
        $this->command->info('Created monuments: 1 pending, 1 approved');
    }
}
