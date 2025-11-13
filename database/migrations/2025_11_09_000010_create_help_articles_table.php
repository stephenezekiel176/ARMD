<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('help_articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->longText('content');
            $table->string('category'); // Account Settings, Troubleshooting, Getting Started, etc.
            $table->json('tags')->nullable();
            $table->json('related_articles')->nullable(); // IDs of related articles
            $table->json('attachments')->nullable(); // Screenshots, PDFs, etc.
            $table->json('video_tutorials')->nullable();
            $table->enum('article_type', ['faq', 'guide', 'tutorial', 'troubleshooting', 'announcement'])->default('faq');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('helpful_count')->default(0);
            $table->unsignedInteger('not_helpful_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'published_at']);
            $table->index('category');
            $table->index('article_type');
            $table->fullText(['title', 'content']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('help_articles');
    }
};
