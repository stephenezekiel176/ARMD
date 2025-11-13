<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CalendarEvent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'all_day',
        'event_type',
        'color',
        'location',
        'department_id',
        'participants',
        'is_recurring',
        'recurrence_pattern',
        'recurrence_end_date',
        'send_reminder',
        'created_by',
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'all_day' => 'boolean',
        'participants' => 'array',
        'is_recurring' => 'boolean',
        'recurrence_end_date' => 'date',
        'send_reminder' => 'boolean',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', today())
                    ->orderBy('event_date', 'asc');
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('event_date', $date);
    }

    public function scopeByMonth($query, $year, $month)
    {
        return $query->whereYear('event_date', $year)
                    ->whereMonth('event_date', $month);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('event_type', $type);
    }

    // Methods
    public function isToday()
    {
        return $this->event_date->isToday();
    }

    public function isUpcoming()
    {
        return $this->event_date->isFuture();
    }

    public function isPast()
    {
        return $this->event_date->isPast();
    }
}
