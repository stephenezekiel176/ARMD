<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('agenda')->nullable();
            $table->dateTime('meeting_date');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('location')->nullable();
            $table->string('meeting_link')->nullable(); // For virtual meetings
            $table->enum('type', ['team', 'department', 'company_wide', 'board', 'other'])->default('team');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $table->json('participants')->nullable(); // Expected participants (user IDs)
            $table->json('attendees')->nullable(); // Actual attendees (user IDs)
            $table->json('minutes')->nullable(); // Meeting minutes/notes
            $table->json('attachments')->nullable();
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled', 'postponed'])->default('scheduled');
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('meeting_date');
            $table->index(['status', 'meeting_date']);
            $table->index('department_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
