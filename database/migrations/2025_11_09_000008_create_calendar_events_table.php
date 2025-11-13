<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('event_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->boolean('all_day')->default(false);
            $table->enum('event_type', ['holiday', 'company_event', 'deadline', 'birthday', 'anniversary', 'meeting', 'workshop', 'training', 'other'])->default('company_event');
            $table->string('color')->default('#3b82f6'); // For calendar display
            $table->string('location')->nullable();
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $table->json('participants')->nullable(); // Relevant users
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_pattern')->nullable(); // daily, weekly, monthly, yearly
            $table->date('recurrence_end_date')->nullable();
            $table->boolean('send_reminder')->default(true);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('event_date');
            $table->index(['event_type', 'event_date']);
            $table->index('department_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
