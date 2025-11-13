<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_threads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->foreignId('category_id')->constrained('forum_categories')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->boolean('is_solved')->default(false);
            $table->unsignedBigInteger('solved_post_id')->nullable(); // Foreign key added later to avoid circular dependency
            $table->json('tags')->nullable();
            $table->json('attachments')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('reply_count')->default(0);
            $table->timestamp('last_activity_at')->nullable();
            $table->foreignId('last_post_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('category_id');
            $table->index(['is_pinned', 'last_activity_at']);
            $table->index('user_id');
            $table->fullText(['title', 'content']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_threads');
    }
};
