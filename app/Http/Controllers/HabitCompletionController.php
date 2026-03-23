<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitCompletion;

class HabitCompletionController extends Controller
{
    public function store(Habit $habit)
    {
        abort_unless($habit->user_id === auth()->id(), 404);

        $today = now()->toDateString();

        if ($habit->completions()->where('completed_on', $today)->exists()) {
            return response()->json([
                'message' => 'Already completed today',
                'habit' => $habit->fresh(['completions']),
            ], 200);
        }

        $habit->completions()->create(['completed_on' => $today]);
        $habit->incrementStreak();

        return response()->json([
            'habit' => $habit->fresh(['completions']),
        ], 201);
    }

    public function destroy(Habit $habit, HabitCompletion $completion)
    {
        abort_unless($habit->user_id === auth()->id(), 404);

        $today = now()->toDateString();
        abort_unless($completion->completed_on->toDateString() === $today, 422);

        $completion->delete();

        $latest = $habit->completions()->orderByDesc('completed_on')->first();
        $habit->last_completed_at = $latest?->completed_on;
        $habit->current_streak = max(0, $habit->current_streak - 1);
        $habit->save();

        return response()->json([
            'habit' => $habit->fresh(['completions']),
        ], 200);
    }
}
