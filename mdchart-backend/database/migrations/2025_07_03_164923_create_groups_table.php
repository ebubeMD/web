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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('rules')->nullable();
            
            // Group media
            $table->string('avatar')->nullable();
            $table->string('cover_photo')->nullable();
            $table->json('gallery')->nullable();
            
            // Group settings
            $table->enum('privacy', ['public', 'private', 'secret'])->default('public');
            $table->enum('join_approval', ['automatic', 'admin_approval', 'invite_only'])->default('automatic');
            $table->boolean('allow_posts')->default(true);
            $table->boolean('allow_photos')->default(true);
            $table->boolean('allow_videos')->default(true);
            $table->boolean('allow_polls')->default(true);
            $table->boolean('allow_events')->default(true);
            
            // Location and categories
            $table->string('location')->nullable();
            $table->json('location_data')->nullable();
            $table->string('category')->nullable();
            $table->json('tags')->nullable();
            
            // Creator and admin info
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->json('admin_settings')->nullable();
            
            // Group metrics
            $table->integer('members_count')->default(0);
            $table->integer('posts_count')->default(0);
            $table->integer('pending_requests_count')->default(0);
            
            // Group features
            $table->boolean('has_chat')->default(true);
            $table->boolean('has_events')->default(true);
            $table->boolean('has_marketplace')->default(false);
            $table->boolean('has_announcements')->default(true);
            
            // Moderation
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_reported')->default(false);
            $table->json('reports')->nullable();
            $table->text('admin_notes')->nullable();
            
            // Activity tracking
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamp('last_post_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['privacy', 'is_active']);
            $table->index(['category', 'is_active']);
            $table->index(['creator_id', 'is_active']);
            $table->index('last_activity_at');
            $table->fullText(['name', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};