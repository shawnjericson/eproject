<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user with security questions for testing forgot password
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password123'),
                'role' => 'moderator',
                'status' => 'active',
                'security_question_1' => 'Tên thú cưng đầu tiên của bạn là gì?',
                'security_answer_1' => 'meo',
                'security_question_2' => 'Tên trường tiểu học của bạn là gì?',
                'security_answer_2' => 'nguyen du',
                'security_question_3' => 'Màu sắc yêu thích của bạn là gì?',
                'security_answer_3' => 'xanh',
            ]
        );

        echo "Test user created:\n";
        echo "Email: test@example.com\n";
        echo "Password: password123\n";
        echo "Security Questions:\n";
        echo "1. Tên thú cưng đầu tiên của bạn là gì? -> meo\n";
        echo "2. Tên trường tiểu học của bạn là gì? -> nguyen du\n";
        echo "3. Màu sắc yêu thích của bạn là gì? -> xanh\n";
    }
}
