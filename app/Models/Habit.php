<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Habit extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'notes',
        'frequency',
        'difficulty',
        'current_streak',
        'max_streak',
        'last_completed_at',
    ];

    protected $casts = [
        'last_completed_at' => 'datetime',
        'frequency' => 'array',
    ];

    public function completions()
    {
        return $this->hasMany(HabitCompletion::class);
    }

    public function goals()
    {
        return $this->belongsToMany(Goal::class)->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function incrementStreak()
    {
        if ($this->last_completed_at && $this->last_completed_at->isBefore(now()->subDay())) {
            $this->current_streak = 0;
        } else {
            $this->current_streak += 1;
        }

        if ($this->current_streak > $this->max_streak) {
            $this->max_streak = $this->current_streak;
        }

        $this->last_completed_at = now();

        $this->save();
    }

    public function getPointsAttribute()
    {
        return match ($this->difficulty) {
            'trivial' => 1,
            'easy' => 2,
            'medium' => 3,
            'hard' => 4,
            'extreme' => 5,
            default => 1,
        };
    }

    public function getFrequencyDisplayAttribute()
    {
        $frequency = $this->frequency;
        $count = $frequency['count'] ?? 1;
        $type = $frequency['type'] ?? 'daily';

        if ($count === 1) {
            return "1 time {$type}";
        }

        return "{$count} times {$type}";
    }
}
