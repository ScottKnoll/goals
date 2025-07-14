<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HabitController extends Controller
{
    public function index()
    {
        $habits = Habit::where('user_id', auth()->id())->get();

        return Inertia::render('habits/Index', [
            'habits' => $habits,
        ]);
    }

    public function create()
    {
        return Inertia::render('habits/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'frequency' => 'required|string|max:255',
            'difficulty' => 'required|string|max:255',
            'current_streak' => 'nullable|integer',
            'max_streak' => 'nullable|integer',
            'last_completed_at' => 'nullable|date',
        ]);

        $habit = $request->user()->habits()->create($validated);

        return redirect()->route('habits.index')->with('success', 'Habit created!');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }
}
