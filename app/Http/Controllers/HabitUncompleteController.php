<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use Illuminate\Support\Facades\Auth;

class HabitUncompleteController extends Controller
{
    public function __invoke(Habit $habit)
    {
        abort_unless($habit->user_id === Auth::id(), 404);

        $today = now()->toDateString();

        $habit->completions()->where('completed_on', $today)->delete();

        $latest = $habit->completions()->orderByDesc('completed_on')->first();
        $habit->last_completed_at = $latest?->completed_on;
        $habit->current_streak = max(0, $habit->current_streak - 1);
        $habit->save();

        return response()->json([
            'habit' => $habit->fresh(['completions']),
        ], 200);
    }
}
