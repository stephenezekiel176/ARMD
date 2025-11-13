<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['video', 'ebook']);
            $table->string('file_path');
            $table->text('description');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->foreignId('facilitator_id')->constrained('users')->onDelete('cascade');
            $table->integer('duration')->comment('Duration in minutes');
            $table->boolean('is_previewable')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
