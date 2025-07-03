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
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('reactable'); // Can react to posts, comments, messages, etc.
            
            // Reaction types
            $table->enum('type', [
                'like', 'love', 'care', 'haha', 'wow', 'sad', 'angry',
                'dislike', 'heart', 'fire', 'clap', 'thumbs_up', 'thumbs_down',
                'celebrate', 'support', 'insightful', 'curious'
            ])->default('like');
            
            // Reaction intensity (for future features)
            $table->integer('intensity')->default(1); // 1-5 scale
            
            // Reaction context
            $table->text('comment')->nullable(); // Optional comment with reaction
            $table->boolean('is_anonymous')->default(false);
            
            // Tracking
            $table->timestamp('reacted_at');
            $table->ipAddress('ip_address')->nullable();
            
            $table->timestamps();
            
            // Constraints and indexes
            $table->unique(['user_id', 'reactable_type', 'reactable_id']);
            $table->index(['reactable_type', 'reactable_id', 'type']);
            $table->index(['user_id', 'created_at']);
            $table->index(['type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reactions');
    }
};