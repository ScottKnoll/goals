<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Inertia\Inertia;

class GoalController extends Controller
{
    public function index()
    {
        $goals = Goal::where('user_id', auth()->id())
            ->with(['milestones', 'habits', 'targetParameters'])
            ->withCount(['milestones', 'habits', 'targetParameters'])
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('goals/Index', [
            'goals' => $goals,
        ]);
    }

    public function create()
    {
        return Inertia::render('goals/Create');
    }

    public function store()
    {
        $validated = request()->validate([
            'title' => 'required|max:255',
            'category' => 'required|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|max:1000',
        ]);

        $validated['user_id'] = auth()->id();

        Goal::create($validated);

        return redirect()->route('goals.index')
            ->with('success', 'Goal created successfully!');
    }

    public function show(Goal $goal)
    {
        $goal->load(['milestones', 'habits', 'targetParameters']);
        $goal->loadCount(['milestones', 'habits', 'targetParameters']);

        return Inertia::render('goals/Show', [
            'goal' => $goal,
        ]);
    }

    public function edit(Goal $goal)
    {
        return Inertia::render('goals/Edit', [
            'goal' => $goal,
        ]);
    }

    public function update(Goal $goal)
    {
        $validated = request()->validate([
            'title' => 'required|max:255',
            'category' => 'required|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|max:1000',
        ]);

        $goal->update($validated);

        return redirect()->route('goals.index')
            ->with('success', 'Goal updated successfully!');
    }

    public function destroy(Goal $goal)
    {
        $goal->delete();

        return redirect()->route('goals.index')
            ->with('success', 'Goal deleted successfully!');
    }
}
