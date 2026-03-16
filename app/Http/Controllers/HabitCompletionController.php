<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use Illuminate\Support\Facades\Auth;

class HabitCompletionController extends Controller
{
    public function __invoke(Habit $habit)
    {
        abort_unless($habit->user_id === Auth::id(), 404);

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
}
