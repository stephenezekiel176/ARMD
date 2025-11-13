<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('practical_guides', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->longText('content'); // Full guide content
            $table->json('steps')->nullable(); // Step-by-step instructions
            $table->string('category'); // Topic category
            $table->string('difficulty_level')->default('beginner'); // beginner, intermediate, advanced
            $table->unsignedInteger('estimated_time')->nullable(); // Time in minutes to complete
            $table->json('prerequisites')->nullable();
            $table->json('tools_required')->nullable();
            $table->json('images')->nullable();
            $table->json('videos')->nullable();
            $table->json('attachments')->nullable(); // Downloadable resources
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'published_at']);
            $table->index('category');
            $table->index('difficulty_level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practical_guides');
    }
};
