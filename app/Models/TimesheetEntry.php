<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TimesheetEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'time',
        'type',
        'latitude',
        'longitude',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    public function scopeCheckIns($query)
    {
        return $query->where('type', 'check_in');
    }

    public function scopeCheckOuts($query)
    {
        return $query->where('type', 'check_out');
    }

    public function getDurationAttribute()
    {
        if ($this->type !== 'check_out') {
            return null;
        }

        $checkIn = $this->where('user_id', $this->user_id)
            ->where('date', $this->date)
            ->where('type', 'check_in')
            ->where('time', '<', $this->time)
            ->latest('time')
            ->first();

        if (!$checkIn) {
            return null;
        }

        $duration = Carbon::parse($checkIn->time)->diffInMinutes(Carbon::parse($this->time));
        $hours = floor($duration / 60);
        $minutes = $duration % 60;

        return sprintf('%02d:%02d', $hours, $minutes);
    }

    public function setTimeAttribute($value)
    {
        $this->attributes['time'] = $value ? Carbon::parse($value)->format('H:i:s') : null;
    }

    public function getFormattedTimeAttribute()
    {
        return $this->time ? Carbon::parse($this->time)->format('H:i:s') : null;
    }

    public function getFormattedLocationAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return "{$this->latitude}, {$this->longitude}";
        }
        return null;
    }
}
