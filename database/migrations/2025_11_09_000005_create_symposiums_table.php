<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('symposiums', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->longText('content');
            $table->text('theme')->nullable();
            $table->json('keynote_speakers')->nullable();
            $table->json('panelists')->nullable();
            $table->json('sessions')->nullable(); // Different sessions with topics
            $table->json('attendees')->nullable();
            $table->unsignedInteger('attendee_count')->default(0);
            $table->dateTime('symposium_date');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->string('location')->nullable();
            $table->string('venue')->nullable();
            $table->json('proceedings')->nullable(); // Symposium proceedings/documents
            $table->json('images')->nullable();
            $table->json('recordings')->nullable();
            $table->text('outcomes')->nullable();
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('symposium_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('symposiums');
    }
};
