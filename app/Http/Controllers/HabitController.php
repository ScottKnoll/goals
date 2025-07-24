<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use Inertia\Inertia;

class HabitController extends Controller
{
    public function index()
    {
        $habits = Habit::where('user_id', auth()->id())
            ->with('completions')
            ->get();

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
            'frequency' => 'required|array',
            'frequency.type' => 'required|in:daily,weekly,monthly',
            'frequency.count' => 'required|integer|min:1|max:10',
            'difficulty' => 'required|in:trivial,easy,medium,hard,extreme',
            'category' => 'nullable|max:255',
        ]);

        $validated['user_id'] = auth()->id();

        Habit::create($validated);

        return redirect()->route('habits.index')->with('success', 'Habit created!');
    }

    public function show($id)
    {
        //
    }

    public function edit(Habit $habit)
    {
        return Inertia::render('habits/Edit', [
            'habit' => $habit,
        ]);
    }

    public function update(Habit $habit)
    {
        $validated = request()->validate([
            'title' => 'required|max:255',
            'notes' => 'nullable',
            'frequency' => 'required|array',
            'frequency.type' => 'required|in:daily,weekly,monthly',
            'frequency.count' => 'required|integer|min:1|max:10',
            'difficulty' => 'required|in:trivial,easy,medium,hard,extreme',
            'category' => 'nullable|max:255',
        ]);

        $habit->update($validated);

        return redirect()->route('habits.index')->with('success', 'Habit updated!');
    }
}
