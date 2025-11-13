<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meeting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'agenda',
        'meeting_date',
        'start_time',
        'end_time',
        'location',
        'meeting_link',
        'type',
        'department_id',
        'participants',
        'attendees',
        'minutes',
        'attachments',
        'status',
        'organizer_id',
    ];

    protected $casts = [
        'participants' => 'array',
        'attendees' => 'array',
        'minutes' => 'array',
        'attachments' => 'array',
        'meeting_date' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Relationships
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('meeting_date', '>', now())
                    ->orderBy('meeting_date', 'asc');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed')
                    ->orderBy('meeting_date', 'desc');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('meeting_date', today())
                    ->orderBy('start_time', 'asc');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Methods
    public function addAttendee($userId)
    {
        $attendees = $this->attendees ?? [];
        if (!in_array($userId, $attendees)) {
            $attendees[] = $userId;
            $this->update(['attendees' => $attendees]);
        }
    }

    public function isUpcoming()
    {
        return $this->status === 'scheduled' && $this->meeting_date->gt(now());
    }

    public function hasStarted()
    {
        return now()->gte($this->start_time);
    }

    public function hasEnded()
    {
        return now()->gte($this->end_time);
    }
}
