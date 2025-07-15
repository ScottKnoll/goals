<?php

namespace App\Http\Controllers;

use App\Models\Habit;
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

    public function store()
    {
        $validated = request()->validate([
            'title' => 'required|max:255',
            'notes' => 'nullable',
            'frequency' => 'required|max:255',
            'difficulty' => 'required|max:255',
            'current_streak' => 'nullable|integer',
            'max_streak' => 'nullable|integer',
            'last_completed_at' => 'nullable|date',
        ]);

        $habit = auth()->user()->habits()->create($validated);

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

    public function update($id)
    {
        //
    }
}
