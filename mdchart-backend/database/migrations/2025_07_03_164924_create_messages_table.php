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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            
            // Message content
            $table->text('content')->nullable();
            $table->text('content_html')->nullable(); // Processed HTML
            $table->enum('type', ['text', 'image', 'video', 'audio', 'file', 'voice', 'location', 'contact', 'sticker', 'gif'])->default('text');
            
            // Media and attachments
            $table->json('media')->nullable(); // Images, videos, files
            $table->json('attachments')->nullable(); // File attachments
            $table->string('voice_note')->nullable(); // Voice message file
            $table->integer('voice_duration')->nullable(); // Duration in seconds
            
            // Message features
            $table->json('mentions')->nullable(); // Mentioned users
            $table->foreignId('reply_to_id')->nullable()->constrained('messages')->onDelete('set null');
            $table->json('forwarded_from')->nullable(); // Original message info
            
            // Location sharing
            $table->json('location_data')->nullable(); // Lat, lng, place info
            $table->string('location_name')->nullable();
            
            // Message status
            $table->boolean('is_edited')->default(false);
            $table->timestamp('edited_at')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->timestamp('deleted_at')->nullable();
            $table->enum('delete_type', ['for_me', 'for_everyone'])->nullable();
            
            // Delivery and read status
            $table->timestamp('delivered_at')->nullable();
            $table->json('read_by')->nullable(); // Array of user_id => timestamp
            $table->boolean('is_important')->default(false);
            $table->boolean('is_starred')->default(false);
            
            // Reactions
            $table->json('reactions')->nullable(); // Array of reactions with user_ids
            
            // End-to-end encryption
            $table->boolean('is_encrypted')->default(false);
            $table->text('encryption_key')->nullable();
            
            // Moderation
            $table->boolean('is_reported')->default(false);
            $table->json('reports')->nullable();
            $table->boolean('is_hidden')->default(false);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['conversation_id', 'created_at']);
            $table->index(['sender_id', 'created_at']);
            $table->index(['type', 'created_at']);
            $table->index(['is_deleted', 'created_at']);
            $table->fullText(['content']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};