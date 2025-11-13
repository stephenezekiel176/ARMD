<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thread_id')->constrained('forum_threads')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('forum_posts')->onDelete('cascade');
            $table->longText('content');
            $table->json('attachments')->nullable();
            $table->json('images')->nullable();
            $table->unsignedInteger('likes')->default(0);
            $table->boolean('is_solution')->default(false);
            $table->boolean('is_moderated')->default(false);
            $table->text('moderation_note')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('thread_id');
            $table->index('user_id');
            $table->index('parent_id');
            $table->index('created_at');
        });

        // Add foreign key for solved_post_id in threads table
        Schema::table('forum_threads', function (Blueprint $table) {
            // This was already defined but may need adjustment after posts table exists
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_posts');
    }
};
