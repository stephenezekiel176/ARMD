<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personnel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('facilitator_id')->constrained('users')->onDelete('cascade');
            $table->string('folder_path');
            $table->timestamps();

            $table->unique(['user_id', 'facilitator_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personnel');
    }
};