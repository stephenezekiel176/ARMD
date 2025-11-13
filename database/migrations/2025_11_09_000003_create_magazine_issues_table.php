<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('magazine_issues', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('issue_number')->unique(); // e.g., "Vol 1, Issue 3"
            $table->text('description');
            $table->longText('content');
            $table->string('cover_image')->nullable();
            $table->json('sections')->nullable(); // Company performance, initiatives, stories, etc.
            $table->json('images')->nullable();
            $table->string('pdf_file')->nullable(); // Full magazine PDF
            $table->foreignId('editor_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->enum('type', ['internal', 'external', 'both'])->default('internal');
            $table->timestamp('published_at')->nullable();
            $table->date('issue_date');
            $table->unsignedInteger('downloads')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'published_at']);
            $table->index('issue_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magazine_issues');
    }
};
