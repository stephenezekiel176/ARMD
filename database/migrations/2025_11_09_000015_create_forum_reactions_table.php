<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('post_id')->constrained('forum_posts')->onDelete('cascade');
            $table->enum('type', ['like', 'helpful', 'thanks', 'insightful'])->default('like');
            $table->timestamps();
            
            $table->unique(['user_id', 'post_id', 'type']);
            $table->index('post_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_reactions');
    }
};
