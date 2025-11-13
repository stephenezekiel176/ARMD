<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('help_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('help_article_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('is_helpful');
            $table->text('comment')->nullable();
            $table->text('suggestion')->nullable();
            $table->timestamps();
            
            $table->index(['help_article_id', 'is_helpful']);
            $table->unique(['help_article_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('help_feedbacks');
    }
};
