<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->json('images')->nullable(); // Multiple images
            $table->json('videos')->nullable(); // Video embeds/URLs
            $table->json('hyperlinks')->nullable(); // Related links
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['news', 'report', 'opinion', 'press_release'])->default('news');
            $table->enum('status', ['draft', 'published', 'breaking', 'archived'])->default('draft');
            $table->string('category'); // world, sports, business, entertainment, etc.
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_breaking')->default(false);
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'published_at']);
            $table->index(['category', 'is_breaking']);
            $table->index('author_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_articles');
    }
};
