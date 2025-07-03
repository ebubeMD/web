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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('group_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('page_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('shared_post_id')->nullable()->constrained('posts')->onDelete('cascade');
            
            // Content
            $table->text('content')->nullable();
            $table->text('content_html')->nullable(); // Processed HTML with mentions, hashtags
            $table->json('media')->nullable(); // Images, videos, files
            $table->string('link_preview')->nullable(); // URL preview
            $table->json('link_preview_data')->nullable(); // Scraped link data
            
            // Post settings
            $table->enum('privacy', ['public', 'friends', 'private', 'custom'])->default('public');
            $table->json('privacy_custom')->nullable(); // Custom privacy settings
            $table->boolean('allow_comments')->default(true);
            $table->boolean('allow_reactions')->default(true);
            $table->boolean('allow_sharing')->default(true);
            
            // Post type and features
            $table->enum('type', ['text', 'photo', 'video', 'link', 'poll', 'event', 'product', 'article'])->default('text');
            $table->json('poll_data')->nullable(); // Poll options and votes
            $table->json('event_data')->nullable(); // Event details
            $table->json('product_data')->nullable(); // Marketplace product details
            
            // Location and tags
            $table->string('location')->nullable();
            $table->json('location_data')->nullable(); // Lat, lng, place info
            $table->json('tagged_users')->nullable(); // Array of user IDs
            $table->json('hashtags')->nullable(); // Extracted hashtags
            $table->json('mentions')->nullable(); // Mentioned users
            
            // Scheduling and publishing
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_published')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_pinned')->default(false);
            
            // Engagement metrics (for performance)
            $table->integer('likes_count')->default(0);
            $table->integer('loves_count')->default(0);
            $table->integer('laughs_count')->default(0);
            $table->integer('angry_count')->default(0);
            $table->integer('sad_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->integer('shares_count')->default(0);
            $table->integer('views_count')->default(0);
            
            // Admin and moderation
            $table->boolean('is_approved')->default(true);
            $table->boolean('is_reported')->default(false);
            $table->json('reports')->nullable(); // Report details
            $table->text('admin_notes')->nullable();
            
            // Edit history
            $table->json('edit_history')->nullable();
            $table->timestamp('last_edited_at')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'is_published', 'published_at']);
            $table->index(['group_id', 'is_published']);
            $table->index(['page_id', 'is_published']);
            $table->index(['privacy', 'is_published', 'published_at']);
            $table->index(['type', 'is_published']);
            $table->index('scheduled_at');
            $table->fullText(['content']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};