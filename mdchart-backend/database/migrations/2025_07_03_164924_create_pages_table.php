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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('about')->nullable();
            
            // Page media
            $table->string('avatar')->nullable();
            $table->string('cover_photo')->nullable();
            $table->json('gallery')->nullable();
            
            // Page type and category
            $table->enum('type', ['business', 'organization', 'brand', 'public_figure', 'community', 'entertainment'])->default('business');
            $table->string('category')->nullable();
            $table->string('subcategory')->nullable();
            
            // Contact information
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->text('address')->nullable();
            $table->json('location_data')->nullable(); // Lat, lng, place info
            
            // Business information
            $table->json('business_hours')->nullable();
            $table->string('price_range')->nullable();
            $table->json('services')->nullable();
            $table->json('products')->nullable();
            
            // Creator and admin info
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            
            // Page metrics
            $table->integer('likes_count')->default(0);
            $table->integer('followers_count')->default(0);
            $table->integer('posts_count')->default(0);
            $table->integer('reviews_count')->default(0);
            $table->decimal('rating_average', 3, 2)->default(0);
            
            // Page features
            $table->boolean('allow_reviews')->default(true);
            $table->boolean('allow_messages')->default(true);
            $table->boolean('allow_posts')->default(true);
            $table->boolean('has_shop')->default(false);
            $table->boolean('has_events')->default(true);
            $table->boolean('has_services')->default(false);
            
            // Verification and status
            $table->boolean('is_verified')->default(false);
            $table->string('verification_type')->nullable(); // blue_check, gold_check, etc.
            $table->timestamp('verified_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_published')->default(true);
            $table->boolean('is_featured')->default(false);
            
            // Promotion and advertising
            $table->boolean('can_promote')->default(false);
            $table->json('promotion_settings')->nullable();
            $table->decimal('ad_budget', 10, 2)->default(0);
            
            // Insights and analytics
            $table->json('insights_data')->nullable();
            $table->timestamp('last_insights_update')->nullable();
            
            // Moderation
            $table->boolean('is_reported')->default(false);
            $table->json('reports')->nullable();
            $table->text('admin_notes')->nullable();
            
            // Activity tracking
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamp('last_post_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['type', 'category', 'is_active']);
            $table->index(['is_verified', 'is_published']);
            $table->index(['creator_id', 'is_active']);
            $table->index('last_activity_at');
            $table->fullText(['name', 'description', 'about']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};