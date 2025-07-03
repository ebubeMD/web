<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Mdchart V2.0 Supreme Edition - Posts Table Migration
 * Revolutionary post system with AI, Blockchain, Gaming, AR/VR integration
 * 
 * Author: Ebube Eze
 * Features: Advanced content types with supreme capabilities
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            // Primary Identification
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->uuid('parent_id')->nullable()->index(); // For reposts/shares
            $table->uuid('original_post_id')->nullable()->index(); // Track original post
            
            // Content Information
            $table->text('content')->nullable();
            $table->text('raw_content')->nullable(); // Original before AI processing
            $table->json('content_versions')->nullable(); // Version history
            $table->enum('content_type', [
                'text', 'image', 'video', 'audio', 'document', 'link', 'poll', 
                'event', 'product', 'nft', 'ar_content', 'vr_content', 
                'gaming_content', 'live_stream', 'story', 'reel'
            ])->default('text');
            $table->json('media_attachments')->nullable();
            $table->json('media_metadata')->nullable(); // Size, duration, format, etc.
            
            // AI & Machine Learning Features
            $table->boolean('ai_generated')->default(false);
            $table->string('ai_model_used')->nullable(); // GPT-4, DALL-E, etc.
            $table->json('ai_generation_params')->nullable();
            $table->text('ai_prompt')->nullable(); // Original prompt used
            $table->decimal('ai_confidence_score', 5, 2)->nullable();
            $table->json('ai_content_analysis')->nullable(); // Sentiment, topics, etc.
            $table->json('ai_suggestions')->nullable(); // AI-generated improvements
            $table->boolean('ai_fact_checked')->default(false);
            $table->json('ai_fact_check_results')->nullable();
            $table->decimal('ai_engagement_prediction', 5, 2)->nullable();
            $table->json('ai_auto_tags')->nullable();
            $table->json('ai_translation')->nullable(); // Multi-language support
            
            // Blockchain & Web3 Features
            $table->boolean('nft_enabled')->default(false);
            $table->string('nft_contract_address')->nullable();
            $table->string('nft_token_id')->nullable();
            $table->decimal('nft_price', 20, 8)->nullable();
            $table->string('nft_marketplace_url')->nullable();
            $table->json('blockchain_metadata')->nullable();
            $table->string('ipfs_hash')->nullable(); // Content stored on IPFS
            $table->boolean('monetized')->default(false);
            $table->decimal('price_to_view', 10, 8)->nullable(); // Paid content
            $table->decimal('creator_earnings', 10, 8)->default(0);
            $table->json('smart_contract_data')->nullable();
            $table->boolean('decentralized_storage')->default(false);
            
            // Gaming & Entertainment Features
            $table->string('game_id')->nullable()->index();
            $table->json('gaming_data')->nullable(); // Scores, achievements, etc.
            $table->boolean('tournament_content')->default(false);
            $table->uuid('tournament_id')->nullable()->index();
            $table->json('gaming_stats')->nullable();
            $table->decimal('gaming_reward', 10, 2)->nullable();
            $table->boolean('streaming_content')->default(false);
            $table->string('stream_url')->nullable();
            $table->json('stream_metadata')->nullable();
            $table->boolean('esports_content')->default(false);
            $table->json('leaderboard_data')->nullable();
            
            // AR/VR Features
            $table->boolean('ar_enabled')->default(false);
            $table->boolean('vr_enabled')->default(false);
            $table->json('ar_filters')->nullable();
            $table->json('vr_environment')->nullable();
            $table->json('spatial_audio_data')->nullable();
            $table->json('ar_tracking_data')->nullable();
            $table->string('vr_room_id')->nullable();
            $table->json('mixed_reality_settings')->nullable();
            $table->json('ar_anchor_points')->nullable();
            $table->boolean('requires_ar_device')->default(false);
            $table->boolean('requires_vr_device')->default(false);
            
            // Privacy & Visibility Settings
            $table->enum('visibility', [
                'public', 'friends', 'friends_of_friends', 'custom', 'private',
                'subscribers', 'premium', 'vip', 'dao_members'
            ])->default('public');
            $table->json('custom_audience')->nullable(); // Specific users/groups
            $table->boolean('allow_comments')->default(true);
            $table->boolean('allow_shares')->default(true);
            $table->boolean('allow_reactions')->default(true);
            $table->boolean('mature_content')->default(false);
            $table->json('content_warnings')->nullable();
            $table->enum('content_rating', ['G', 'PG', 'PG-13', 'R', 'NC-17'])->nullable();
            
            // Engagement & Analytics
            $table->bigInteger('views_count')->default(0);
            $table->bigInteger('likes_count')->default(0);
            $table->bigInteger('shares_count')->default(0);
            $table->bigInteger('comments_count')->default(0);
            $table->bigInteger('saves_count')->default(0);
            $table->decimal('engagement_rate', 5, 2)->default(0);
            $table->json('engagement_analytics')->nullable();
            $table->json('demographic_data')->nullable();
            $table->json('reach_metrics')->nullable();
            $table->json('conversion_metrics')->nullable();
            $table->decimal('virality_score', 5, 2)->default(0);
            
            // Scheduling & Publishing
            $table->enum('status', [
                'draft', 'scheduled', 'published', 'archived', 'deleted',
                'under_review', 'flagged', 'promoted', 'featured'
            ])->default('published');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // For stories, temporary content
            $table->boolean('auto_delete')->default(false);
            $table->integer('auto_delete_days')->nullable();
            
            // Location & Geography
            $table->string('location_name')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->json('location_metadata')->nullable();
            $table->boolean('location_enabled')->default(false);
            $table->string('place_id')->nullable(); // Google Places ID
            
            // Tagging & Categorization
            $table->json('hashtags')->nullable();
            $table->json('mentioned_users')->nullable();
            $table->json('tagged_products')->nullable();
            $table->json('categories')->nullable();
            $table->json('topics')->nullable();
            $table->string('language', 5)->default('en');
            $table->json('keywords')->nullable();
            
            // Collaboration Features
            $table->json('collaborators')->nullable(); // Co-authors
            $table->boolean('open_for_collaboration')->default(false);
            $table->json('collaboration_settings')->nullable();
            $table->uuid('brand_partnership_id')->nullable();
            $table->boolean('sponsored_content')->default(false);
            $table->json('sponsor_disclosure')->nullable();
            
            // Moderation & Safety
            $table->boolean('flagged')->default(false);
            $table->json('flags')->nullable(); // Spam, inappropriate, etc.
            $table->integer('report_count')->default(0);
            $table->json('moderation_actions')->nullable();
            $table->boolean('ai_moderated')->default(false);
            $table->json('ai_moderation_results')->nullable();
            $table->enum('content_safety_level', ['safe', 'caution', 'warning', 'blocked'])->default('safe');
            $table->timestamp('last_moderated_at')->nullable();
            
            // Live Features
            $table->boolean('is_live')->default(false);
            $table->timestamp('live_started_at')->nullable();
            $table->timestamp('live_ended_at')->nullable();
            $table->integer('live_viewers')->default(0);
            $table->integer('max_live_viewers')->default(0);
            $table->json('live_chat_data')->nullable();
            $table->boolean('live_recording_enabled')->default(false);
            
            // E-commerce Integration
            $table->boolean('has_products')->default(false);
            $table->json('product_catalog')->nullable();
            $table->boolean('shoppable')->default(false);
            $table->json('shopping_tags')->nullable();
            $table->decimal('product_revenue', 10, 2)->default(0);
            
            // Advanced Features
            $table->json('custom_metadata')->nullable(); // Extensible data
            $table->json('api_data')->nullable(); // Third-party integrations
            $table->json('feature_flags')->nullable();
            $table->json('experiment_data')->nullable();
            $table->string('source_platform')->nullable(); // Cross-posted from
            $table->string('external_id')->nullable(); // External platform ID
            
            // Performance Optimization
            $table->json('cache_data')->nullable();
            $table->timestamp('cache_expires_at')->nullable();
            $table->boolean('requires_processing')->default(false);
            $table->json('processing_status')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for Performance
            $table->index(['user_id', 'status', 'created_at']);
            $table->index(['content_type', 'status']);
            $table->index(['visibility', 'published_at']);
            $table->index(['status', 'scheduled_at']);
            $table->index(['is_live', 'created_at']);
            $table->index(['game_id', 'created_at']);
            $table->index(['tournament_id', 'created_at']);
            $table->index(['nft_enabled', 'created_at']);
            $table->index(['ai_generated', 'created_at']);
            $table->index(['location_enabled', 'latitude', 'longitude']);
            $table->index(['mature_content', 'content_rating']);
            $table->index(['virality_score', 'engagement_rate']);
            $table->index(['hashtags'], 'posts_hashtags_gin_index')->algorithm('gin');
            
            // Full-text search
            $table->fullText(['content'], 'posts_content_fulltext');
            
            // Foreign Key Constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('original_post_id')->references('id')->on('posts')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};