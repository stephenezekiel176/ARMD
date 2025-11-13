<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Symposium extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'theme',
        'keynote_speakers',
        'panelists',
        'sessions',
        'attendees',
        'attendee_count',
        'symposium_date',
        'start_time',
        'end_time',
        'location',
        'venue',
        'proceedings',
        'images',
        'recordings',
        'outcomes',
        'status',
    ];

    protected $casts = [
        'keynote_speakers' => 'array',
        'panelists' => 'array',
        'sessions' => 'array',
        'attendees' => 'array',
        'proceedings' => 'array',
        'images' => 'array',
        'recordings' => 'array',
        'symposium_date' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($symposium) {
            if (empty($symposium->slug)) {
                $symposium->slug = Str::slug($symposium->title);
            }
        });
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming')
                    ->where('symposium_date', '>', now())
                    ->orderBy('symposium_date', 'asc');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed')
                    ->orderBy('symposium_date', 'desc');
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
        return $this->status === 'upcoming' && $this->symposium_date->gt(now());
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }
}
