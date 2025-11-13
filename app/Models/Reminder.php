<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reminder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'message',
        'remind_at',
        'type',
        'priority',
        'remindable_type',
        'remindable_id',
        'recipients',
        'sent_to',
        'delivery_method',
        'is_sent',
        'sent_at',
        'is_recurring',
        'recurrence_pattern',
        'created_by',
    ];

    protected $casts = [
        'remind_at' => 'datetime',
        'recipients' => 'array',
        'sent_to' => 'array',
        'is_sent' => 'boolean',
        'sent_at' => 'datetime',
        'is_recurring' => 'boolean',
    ];

    // Relationships
    public function remindable()
    {
        return $this->morphTo();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('is_sent', false)
                    ->where('remind_at', '<=', now())
                    ->orderBy('priority', 'desc')
                    ->orderBy('remind_at', 'asc');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('is_sent', false)
                    ->where('remind_at', '>', now())
                    ->orderBy('remind_at', 'asc');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // Methods
    public function markAsSent()
    {
        $this->update([
            'is_sent' => true,
            'sent_at' => now(),
        ]);
    }

    public function addSentRecipient($userId)
    {
        $sentTo = $this->sent_to ?? [];
        if (!in_array($userId, $sentTo)) {
            $sentTo[] = $userId;
            $this->update(['sent_to' => $sentTo]);
        }
    }

    public function isDue()
    {
        return !$this->is_sent && $this->remind_at->lte(now());
    }
}
