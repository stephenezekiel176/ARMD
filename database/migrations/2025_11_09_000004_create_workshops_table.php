<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workshops', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->longText('overview');
            $table->text('outcome')->nullable(); // What was achieved
            $table->text('reason'); // Reason for the workshop
            $table->json('speakers')->nullable(); // Main speakers with their details
            $table->json('attendees')->nullable(); // Recorded attendees (user IDs)
            $table->unsignedInteger('attendee_count')->default(0);
            $table->dateTime('workshop_date');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->string('location')->nullable();
            $table->string('venue')->nullable();
            $table->json('materials')->nullable(); // Documents, slides, recordings
            $table->json('images')->nullable();
            $table->string('recording_url')->nullable();
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('workshop_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workshops');
    }
};
