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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');
            
            // Content
            $table->text('content');
            $table->text('content_html')->nullable(); // Processed HTML with mentions
            $table->json('media')->nullable(); // Images, videos, files, GIFs
            $table->json('attachments')->nullable(); // File attachments
            
            // Comment features
            $table->json('mentions')->nullable(); // Mentioned users
            $table->boolean('is_edited')->default(false);
            $table->timestamp('edited_at')->nullable();
            $table->json('edit_history')->nullable();
            
            // Engagement metrics
            $table->integer('likes_count')->default(0);
            $table->integer('loves_count')->default(0);
            $table->integer('laughs_count')->default(0);
            $table->integer('angry_count')->default(0);
            $table->integer('sad_count')->default(0);
            $table->integer('replies_count')->default(0);
            
            // Moderation
            $table->boolean('is_approved')->default(true);
            $table->boolean('is_reported')->default(false);
            $table->json('reports')->nullable();
            $table->boolean('is_hidden')->default(false);
            $table->text('admin_notes')->nullable();
            
            // Threading and sorting
            $table->integer('depth')->default(0);
            $table->string('thread_path')->nullable(); // For nested comments performance
            $table->integer('sort_order')->default(0);
            
            // Real-time features
            $table->boolean('is_pinned')->default(false);
            $table->timestamp('pinned_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['post_id', 'parent_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['is_approved', 'is_hidden']);
            $table->index('thread_path');
            $table->fullText(['content']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};