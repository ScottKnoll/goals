<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    protected $fillable = [
        'goal_id',
        'title',
        'notes',
        'target_date',
        'completed_at',
    ];

    protected $casts = [
        'target_date' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
