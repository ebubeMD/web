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
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('group_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('page_id')->nullable()->constrained()->onDelete('cascade');
            
            // Story content
            $table->text('content')->nullable(); // Text content
            $table->enum('type', ['text', 'image', 'video', 'boomerang', 'live'])->default('text');
            $table->string('media_url')->nullable(); // Image/video file
            $table->string('thumbnail_url')->nullable(); // Video thumbnail
            $table->integer('duration')->nullable(); // Duration in seconds for videos
            
            // Story styling
            $table->string('background_color')->nullable();
            $table->string('background_gradient')->nullable();
            $table->string('text_color')->nullable();
            $table->string('font_family')->nullable();
            $table->json('stickers')->nullable(); // Applied stickers
            $table->json('effects')->nullable(); // Filters and effects
            $table->json('music')->nullable(); // Background music info
            
            // Story settings
            $table->enum('privacy', ['public', 'friends', 'close_friends', 'custom'])->default('friends');
            $table->json('privacy_custom')->nullable(); // Custom viewer list
            $table->boolean('allow_replies')->default(true);
            $table->boolean('allow_resharing')->default(true);
            $table->boolean('show_viewers')->default(true);
            
            // Story features
            $table->json('polls')->nullable(); // Poll stickers
            $table->json('questions')->nullable(); // Question stickers
            $table->json('quizzes')->nullable(); // Quiz stickers
            $table->json('location')->nullable(); // Location sticker
            $table->json('mentions')->nullable(); // Mentioned users
            $table->json('hashtags')->nullable(); // Hashtag stickers
            
            // Story metrics
            $table->integer('views_count')->default(0);
            $table->integer('replies_count')->default(0);
            $table->integer('shares_count')->default(0);
            $table->integer('screenshot_count')->default(0);
            
            // Story lifecycle
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // Default 24 hours
            $table->boolean('is_archived')->default(false);
            $table->boolean('is_highlighted')->default(false);
            $table->boolean('is_featured')->default(false);
            
            // Live story features
            $table->boolean('is_live')->default(false);
            $table->timestamp('live_started_at')->nullable();
            $table->timestamp('live_ended_at')->nullable();
            $table->integer('live_viewers_count')->default(0);
            
            // Moderation
            $table->boolean('is_reported')->default(false);
            $table->json('reports')->nullable();
            $table->boolean('is_approved')->default(true);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'published_at']);
            $table->index(['expires_at', 'is_archived']);
            $table->index(['type', 'published_at']);
            $table->index(['privacy', 'published_at']);
            $table->index(['is_live', 'live_started_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};