<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->dateTime('remind_at');
            $table->enum('type', ['meeting', 'event', 'deadline', 'task', 'assessment', 'general'])->default('general');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->morphs('remindable'); // Polymorphic: can be linked to meeting, event, etc.
            $table->json('recipients')->nullable(); // User IDs to be reminded
            $table->json('sent_to')->nullable(); // Track who has been sent the reminder
            $table->enum('delivery_method', ['email', 'notification', 'both'])->default('both');
            $table->boolean('is_sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_pattern')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('remind_at');
            $table->index(['is_sent', 'remind_at']);
            // morphs() already creates index for remindable_type and remindable_id
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
