<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Workshop extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'overview',
        'outcome',
        'reason',
        'speakers',
        'attendees',
        'attendee_count',
        'workshop_date',
        'start_time',
        'end_time',
        'location',
        'venue',
        'materials',
        'images',
        'recording_url',
        'status',
    ];

    protected $casts = [
        'speakers' => 'array',
        'attendees' => 'array',
        'materials' => 'array',
        'images' => 'array',
        'workshop_date' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($workshop) {
            if (empty($workshop->slug)) {
                $workshop->slug = Str::slug($workshop->title);
            }
        });
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming')
                    ->where('workshop_date', '>', now())
                    ->orderBy('workshop_date', 'asc');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed')
                    ->orderBy('workshop_date', 'desc');
    }

    public function scopeRecent($query, $limit = 5)
    {
        return $query->completed()
                    ->limit($limit);
    }

    // Methods
    public function addAttendee($userId)
    {
        $attendees = $this->attendees ?? [];
        if (!in_array($userId, $attendees)) {
            $attendees[] = $userId;
            $this->update([
                'attendees' => $attendees,
                'attendee_count' => count($attendees),
            ]);
        }
    }

    public function isUpcoming()
    {
        return $this->status === 'upcoming' && $this->workshop_date->gt(now());
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }
}
