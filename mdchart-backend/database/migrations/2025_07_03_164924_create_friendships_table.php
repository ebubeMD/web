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
        Schema::create('friendships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('friend_id')->constrained('users')->onDelete('cascade');
            
            // Friendship status
            $table->enum('status', ['pending', 'accepted', 'blocked', 'following'])->default('pending');
            $table->enum('type', ['friend', 'follow', 'mutual_follow'])->default('friend');
            
            // Request details
            $table->foreignId('requester_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('requested_at');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('blocked_at')->nullable();
            
            // Privacy and settings
            $table->boolean('show_in_friends_list')->default(true);
            $table->boolean('allow_posts_on_timeline')->default(true);
            $table->boolean('notify_activity')->default(true);
            
            // Close friends feature
            $table->boolean('is_close_friend')->default(false);
            $table->boolean('is_best_friend')->default(false);
            $table->boolean('is_restricted')->default(false);
            
            // Special categorization
            $table->string('relationship_type')->nullable(); // family, work, school, etc.
            $table->json('custom_lists')->nullable(); // Custom friend lists
            
            // Interaction tracking
            $table->integer('interactions_count')->default(0);
            $table->timestamp('last_interaction_at')->nullable();
            
            $table->timestamps();
            
            // Indexes and constraints
            $table->unique(['user_id', 'friend_id']);
            $table->index(['user_id', 'status']);
            $table->index(['friend_id', 'status']);
            $table->index(['requester_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index(['type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('friendships');
    }
};