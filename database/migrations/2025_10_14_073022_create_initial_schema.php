<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Users table
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('avatar')->nullable();
                $table->string('phone')->nullable();
                $table->text('bio')->nullable();
                $table->string('address')->nullable();
                $table->date('date_of_birth')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->enum('role', ['admin', 'moderator'])->default('moderator');
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->string('remember_token')->nullable();
                $table->string('security_question_1')->nullable();
                $table->string('security_answer_1')->nullable();
                $table->string('security_question_2')->nullable();
                $table->string('security_answer_2')->nullable();
                $table->string('security_question_3')->nullable();
                $table->string('security_answer_3')->nullable();
                $table->timestamps();
            });
        }

        // Password reset tokens
        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        // Failed jobs
        if (!Schema::hasTable('failed_jobs')) {
            Schema::create('failed_jobs', function (Blueprint $table) {
                $table->id();
                $table->string('uuid')->unique();
                $table->text('connection');
                $table->text('queue');
                $table->longText('payload');
                $table->longText('exception');
                $table->timestamp('failed_at')->useCurrent();
            });
        }

        // Personal access tokens
        if (!Schema::hasTable('personal_access_tokens')) {
            Schema::create('personal_access_tokens', function (Blueprint $table) {
                $table->id();
                $table->morphs('tokenable');
                $table->string('name');
                $table->string('token', 64)->unique();
                $table->text('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
            });
        }

        // Posts table
        if (!Schema::hasTable('posts')) {
            Schema::create('posts', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->longText('content');
                $table->string('image')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
                $table->timestamp('published_at')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->timestamp('rejected_at')->nullable();
                $table->unsignedBigInteger('rejected_by')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
            });
        }

        // Monuments table
        if (!Schema::hasTable('monuments')) {
            Schema::create('monuments', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->text('history')->nullable();
                $table->longText('content')->nullable();
                $table->string('location')->nullable();
                $table->text('map_embed')->nullable();
                $table->string('zone')->nullable();
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
                $table->boolean('is_world_wonder')->default(false);
                $table->string('image')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
                $table->text('rejection_reason')->nullable();
                $table->timestamp('rejected_at')->nullable();
                $table->unsignedBigInteger('rejected_by')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
            });
        }

        // Gallery table
        if (!Schema::hasTable('gallery')) {
            Schema::create('gallery', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('monument_id');
                $table->string('title');
                $table->string('image_path');
                $table->text('description')->nullable();
                $table->timestamps();

                $table->foreign('monument_id')->references('id')->on('monuments')->onDelete('cascade');
            });
        }

        // Feedbacks table
        if (!Schema::hasTable('feedbacks')) {
            Schema::create('feedbacks', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->text('message');
                $table->integer('rating')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->unsignedBigInteger('monument_id')->nullable();
                $table->timestamp('viewed_at')->nullable();
                $table->timestamps();

                $table->foreign('monument_id')->references('id')->on('monuments')->onDelete('set null');
            });
        }

        // Site settings table
        if (!Schema::hasTable('site_settings')) {
            Schema::create('site_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value');
                $table->text('description')->nullable();
                $table->string('category')->nullable();
                $table->string('type')->nullable();
                $table->integer('min')->nullable();
                $table->integer('max')->nullable();
                $table->timestamps();
            });
        }

        // Post translations table
        if (!Schema::hasTable('post_translations')) {
            Schema::create('post_translations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('post_id');
                $table->string('language', 5);
                $table->string('title');
                $table->text('description')->nullable();
                $table->longText('content')->nullable();
                $table->timestamps();

                $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
                $table->unique(['post_id', 'language']);
            });
        }

        // Monument translations table
        if (!Schema::hasTable('monument_translations')) {
            Schema::create('monument_translations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('monument_id');
                $table->string('language', 5);
                $table->string('title');
                $table->text('description')->nullable();
                $table->text('history')->nullable();
                $table->longText('content')->nullable();
                $table->string('location')->nullable();
                $table->timestamps();

                $table->foreign('monument_id')->references('id')->on('monuments')->onDelete('cascade');
                $table->unique(['monument_id', 'language']);
            });
        }

        // Visitor logs table
        if (!Schema::hasTable('visitor_logs')) {
            Schema::create('visitor_logs', function (Blueprint $table) {
                $table->id();
                $table->string('ip_address');
                $table->text('user_agent');
                $table->timestamp('visited_at');
                $table->timestamps();
            });
        }

        // Contacts table
        if (!Schema::hasTable('contacts')) {
            Schema::create('contacts', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->string('subject');
                $table->text('message');
                $table->enum('status', ['new', 'read', 'replied'])->default('new');
                $table->text('admin_reply')->nullable();
                $table->timestamp('replied_at')->nullable();
                $table->unsignedBigInteger('replied_by')->nullable();
                $table->timestamps();

                $table->foreign('replied_by')->references('id')->on('users')->onDelete('set null');
            });
        }

        // Notifications table
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
        }

        // User notifications table
        if (!Schema::hasTable('user_notifications')) {
            Schema::create('user_notifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('type');
                $table->string('title');
                $table->text('message');
                $table->json('data')->nullable();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('visitor_logs');
        Schema::dropIfExists('monument_translations');
        Schema::dropIfExists('post_translations');
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('feedbacks');
        Schema::dropIfExists('gallery');
        Schema::dropIfExists('monuments');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};