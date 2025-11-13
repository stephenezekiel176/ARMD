<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rater_id')->constrained('users')->onDelete('cascade'); // Who is rating
            $table->foreignId('rated_id')->constrained('users')->onDelete('cascade'); // Who is being rated
            $table->enum('rating_type', ['personnel_to_facilitator', 'facilitator_to_personnel']); 
            $table->unsignedTinyInteger('overall_rating'); // 1-5 stars
            $table->unsignedTinyInteger('communication_rating')->nullable();
            $table->unsignedTinyInteger('knowledge_rating')->nullable();
            $table->unsignedTinyInteger('professionalism_rating')->nullable();
            $table->unsignedTinyInteger('responsiveness_rating')->nullable();
            $table->text('comment')->nullable();
            $table->text('strengths')->nullable();
            $table->text('improvements')->nullable();
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('set null'); // Related course
            $table->foreignId('assessment_id')->nullable()->constrained()->onDelete('set null'); // Related assessment
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('is_approved')->default(true); // For moderation
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['rated_id', 'rating_type']);
            $table->index('rater_id');
            $table->index('overall_rating');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
