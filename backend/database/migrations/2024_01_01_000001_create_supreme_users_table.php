<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Mdchart V2.0 Supreme Edition - Users Table Migration
 * Revolutionary user system with AI, Blockchain, Gaming, AR/VR integration
 * 
 * Author: Ebube Eze
 * Features: 100+ fields for supreme social platform
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            // Primary Identification
            $table->uuid('id')->primary();
            $table->string('username')->unique()->index();
            $table->string('email')->unique()->index();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            
            // Basic Profile Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('display_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'non-binary', 'other', 'prefer_not_to_say'])->nullable();
            $table->text('bio')->nullable();
            $table->string('tagline')->nullable();
            $table->string('location')->nullable();
            $table->json('languages_spoken')->nullable();
            $table->string('timezone')->default('UTC');
            
            // Contact Information
            $table->string('phone')->nullable();
            $table->boolean('phone_verified')->default(false);
            $table->string('website')->nullable();
            $table->json('social_links')->nullable(); // Facebook, Twitter, Instagram, etc.
            
            // Profile Media
            $table->string('avatar')->nullable();
            $table->string('cover_photo')->nullable();
            $table->json('profile_photos')->nullable();
            $table->string('profile_video')->nullable();
            $table->json('profile_theme')->nullable();
            
            // Privacy & Security Settings
            $table->enum('profile_visibility', ['public', 'friends', 'private'])->default('public');
            $table->boolean('searchable')->default(true);
            $table->boolean('allow_friend_requests')->default(true);
            $table->boolean('allow_messages')->default(true);
            $table->boolean('show_online_status')->default(true);
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('two_factor_secret')->nullable();
            $table->json('recovery_codes')->nullable();
            
            // Account Status & Verification
            $table->enum('status', ['active', 'inactive', 'suspended', 'banned', 'pending'])->default('active');
            $table->boolean('verified')->default(false);
            $table->enum('verification_type', ['none', 'email', 'phone', 'identity', 'business', 'celebrity'])->default('none');
            $table->timestamp('verified_at')->nullable();
            $table->json('verification_documents')->nullable();
            $table->string('badge')->nullable(); // Verification badge
            
            // Social Authentication
            $table->json('oauth_providers')->nullable(); // Google, Facebook, Twitter, etc.
            $table->string('google_id')->nullable()->index();
            $table->string('facebook_id')->nullable()->index();
            $table->string('twitter_id')->nullable()->index();
            $table->string('github_id')->nullable()->index();
            $table->string('linkedin_id')->nullable()->index();
            
            // Activity Tracking
            $table->timestamp('last_active_at')->nullable();
            $table->string('last_ip')->nullable();
            $table->string('last_user_agent')->nullable();
            $table->json('login_history')->nullable();
            $table->integer('login_count')->default(0);
            $table->timestamp('last_password_change')->nullable();
            
            // AI & Machine Learning Features
            $table->json('ai_preferences')->nullable(); // AI content generation preferences
            $table->boolean('ai_recommendations_enabled')->default(true);
            $table->boolean('ai_content_generation_enabled')->default(true);
            $table->boolean('ai_moderation_enabled')->default(true);
            $table->json('ai_interaction_history')->nullable();
            $table->json('content_preferences')->nullable(); // Topics, interests, etc.
            $table->json('ai_personality_profile')->nullable(); // AI-analyzed personality traits
            $table->decimal('ai_engagement_score', 5, 2)->default(0); // AI-calculated engagement score
            $table->json('ai_recommendation_feedback')->nullable();
            
            // Blockchain & Web3 Features
            $table->string('wallet_address')->nullable()->index();
            $table->json('crypto_wallets')->nullable(); // Multiple wallet support
            $table->decimal('token_balance', 20, 8)->default(0); // Platform token balance
            $table->json('nft_collection')->nullable(); // Owned NFTs
            $table->boolean('blockchain_enabled')->default(false);
            $table->string('ens_domain')->nullable(); // Ethereum Name Service
            $table->json('defi_positions')->nullable(); // DeFi investments
            $table->decimal('staking_rewards', 20, 8)->default(0);
            $table->json('transaction_history')->nullable();
            $table->boolean('yield_farming_enabled')->default(false);
            
            // Gaming & Entertainment Features
            $table->integer('gaming_level')->default(1);
            $table->bigInteger('gaming_xp')->default(0);
            $table->json('gaming_achievements')->nullable();
            $table->json('gaming_stats')->nullable(); // Games played, wins, losses
            $table->integer('gaming_rank')->nullable();
            $table->decimal('gaming_score', 10, 2)->default(0);
            $table->json('favorite_games')->nullable();
            $table->boolean('streaming_enabled')->default(false);
            $table->json('streaming_settings')->nullable();
            $table->integer('tournament_wins')->default(0);
            $table->decimal('esports_earnings', 10, 2)->default(0);
            
            // AR/VR Features
            $table->json('vr_avatar_settings')->nullable();
            $table->json('ar_preferences')->nullable();
            $table->boolean('vr_enabled')->default(false);
            $table->boolean('ar_enabled')->default(false);
            $table->json('spatial_audio_settings')->nullable();
            $table->json('virtual_spaces_owned')->nullable();
            $table->json('ar_filter_preferences')->nullable();
            $table->json('vr_room_settings')->nullable();
            
            // Business & Professional Features
            $table->boolean('business_account')->default(false);
            $table->string('business_name')->nullable();
            $table->string('business_type')->nullable();
            $table->string('business_category')->nullable();
            $table->text('business_description')->nullable();
            $table->json('business_hours')->nullable();
            $table->string('business_phone')->nullable();
            $table->string('business_email')->nullable();
            $table->json('business_addresses')->nullable();
            $table->boolean('creator_account')->default(false);
            $table->json('creator_settings')->nullable();
            $table->decimal('creator_earnings', 10, 2)->default(0);
            
            // Analytics & Insights
            $table->bigInteger('profile_views')->default(0);
            $table->bigInteger('post_impressions')->default(0);
            $table->bigInteger('post_engagements')->default(0);
            $table->decimal('engagement_rate', 5, 2)->default(0);
            $table->json('analytics_data')->nullable();
            $table->json('audience_insights')->nullable();
            $table->json('growth_metrics')->nullable();
            
            // Subscription & Monetization
            $table->enum('subscription_tier', ['free', 'premium', 'pro', 'enterprise', 'supreme'])->default('free');
            $table->timestamp('subscription_expires_at')->nullable();
            $table->boolean('auto_renew')->default(false);
            $table->json('subscription_features')->nullable();
            $table->decimal('lifetime_earnings', 10, 2)->default(0);
            $table->json('monetization_settings')->nullable();
            
            // Content & Media Preferences
            $table->json('content_filters')->nullable();
            $table->enum('content_quality', ['low', 'medium', 'high', 'ultra'])->default('medium');
            $table->boolean('nsfw_content_enabled')->default(false);
            $table->json('blocked_keywords')->nullable();
            $table->json('interests')->nullable();
            $table->json('followed_topics')->nullable();
            
            // Notification Preferences
            $table->json('notification_settings')->nullable();
            $table->boolean('email_notifications')->default(true);
            $table->boolean('push_notifications')->default(true);
            $table->boolean('sms_notifications')->default(false);
            $table->json('notification_frequency')->nullable();
            
            // Advanced Features
            $table->json('api_keys')->nullable(); // For developer accounts
            $table->json('webhook_urls')->nullable();
            $table->json('custom_fields')->nullable(); // Extensible custom data
            $table->json('feature_flags')->nullable(); // A/B testing and feature toggles
            $table->json('experiment_groups')->nullable();
            $table->decimal('influence_score', 8, 2)->default(0);
            $table->json('network_connections')->nullable();
            
            // Moderation & Safety
            $table->integer('strike_count')->default(0);
            $table->json('moderation_history')->nullable();
            $table->timestamp('last_warned_at')->nullable();
            $table->timestamp('suspended_until')->nullable();
            $table->text('suspension_reason')->nullable();
            $table->json('content_violations')->nullable();
            
            // Location & Geography
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('region')->nullable();
            $table->string('city')->nullable();
            $table->boolean('location_sharing_enabled')->default(false);
            
            // Device & Platform Info
            $table->json('device_info')->nullable();
            $table->json('app_versions')->nullable();
            $table->json('platform_preferences')->nullable();
            $table->boolean('dark_mode')->default(false);
            $table->string('preferred_language', 5)->default('en');
            
            // Referral & Growth
            $table->string('referral_code')->unique()->nullable();
            $table->uuid('referred_by')->nullable();
            $table->integer('referral_count')->default(0);
            $table->decimal('referral_earnings', 10, 2)->default(0);
            
            // Virtual Economy
            $table->bigInteger('virtual_currency')->default(0);
            $table->json('virtual_assets')->nullable();
            $table->json('marketplace_activity')->nullable();
            $table->decimal('marketplace_rating', 3, 2)->default(0);
            $table->integer('marketplace_transactions')->default(0);
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for Performance
            $table->index(['status', 'created_at']);
            $table->index(['verified', 'status']);
            $table->index(['business_account', 'status']);
            $table->index(['subscription_tier', 'status']);
            $table->index(['last_active_at', 'status']);
            $table->index(['gaming_level', 'gaming_score']);
            $table->index(['influence_score', 'verified']);
            $table->index(['token_balance', 'blockchain_enabled']);
            $table->index(['country_code', 'status']);
            $table->index(['ai_engagement_score', 'status']);
            
            // Foreign Key Constraints
            $table->foreign('referred_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};