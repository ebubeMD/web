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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // For group chats
            $table->text('description')->nullable();
            $table->enum('type', ['direct', 'group', 'page', 'announcement'])->default('direct');
            
            // Group chat settings
            $table->string('avatar')->nullable();
            $table->foreignId('creator_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('group_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('page_id')->nullable()->constrained()->onDelete('cascade');
            
            // Conversation settings
            $table->boolean('is_muted')->default(false);
            $table->boolean('is_archived')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->boolean('allow_adding_members')->default(true);
            $table->boolean('allow_media')->default(true);
            $table->boolean('is_encrypted')->default(false);
            
            // Participants count
            $table->integer('participants_count')->default(0);
            $table->integer('messages_count')->default(0);
            
            // Last message info for performance
            $table->foreignId('last_message_id')->nullable()->constrained('messages')->onDelete('set null');
            $table->timestamp('last_message_at')->nullable();
            $table->foreignId('last_message_sender_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Moderation
            $table->boolean('is_reported')->default(false);
            $table->json('reports')->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['type', 'is_active']);
            $table->index(['creator_id', 'type']);
            $table->index(['group_id', 'type']);
            $table->index(['page_id', 'type']);
            $table->index('last_message_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};