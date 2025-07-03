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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('display_name')->nullable();
            $table->text('bio')->nullable();
            $table->date('birthday')->nullable();
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('location')->nullable();
            $table->string('work')->nullable();
            $table->string('education')->nullable();
            $table->string('relationship_status')->nullable();
            
            // Profile media
            $table->string('avatar')->nullable();
            $table->string('cover_photo')->nullable();
            
            // Social Login
            $table->string('google_id')->nullable();
            $table->string('facebook_id')->nullable();
            $table->string('twitter_id')->nullable();
            $table->string('github_id')->nullable();
            
            // Account status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_online')->default(false);
            $table->timestamp('last_seen')->nullable();
            $table->boolean('is_private')->default(false);
            
            // Two-Factor Authentication
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            
            // Privacy Settings
            $table->json('privacy_settings')->nullable();
            $table->json('notification_settings')->nullable();
            
            // Account verification
            $table->string('verification_type')->nullable(); // blue_check, gold_check, etc.
            $table->timestamp('verified_at')->nullable();
            
            // User preferences
            $table->string('language', 5)->default('en');
            $table->string('timezone')->default('UTC');
            $table->enum('theme', ['light', 'dark', 'auto'])->default('auto');
            
            // Counters (for performance)
            $table->integer('posts_count')->default(0);
            $table->integer('followers_count')->default(0);
            $table->integer('following_count')->default(0);
            $table->integer('friends_count')->default(0);
            
            // Admin features
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_moderator')->default(false);
            $table->timestamp('banned_until')->nullable();
            $table->text('ban_reason')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
            
            // Indexes
            $table->index(['is_active', 'is_online']);
            $table->index('last_seen');
            $table->index(['is_verified', 'verification_type']);
            $table->fullText(['username', 'first_name', 'last_name', 'display_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};