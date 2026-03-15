<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Milestone;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GoalMilestoneController extends Controller
{
    public function create(Goal $goal)
    {
        return Inertia::render('goals/milestones/Create', [
            'goal' => $goal->only(['id', 'title']),
        ]);
    }

    public function store(Request $request, Goal $goal)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'notes' => 'nullable|max:1000',
            'target_date' => 'nullable|date',
        ]);

        $goal->milestones()->create($validated);

        return redirect()->route('goals.show', $goal)
            ->with('success', 'Milestone added successfully!');
    }

    public function edit(Goal $goal, Milestone $milestone)
    {
        abort_unless($milestone->goal_id === $goal->id, 404);

        return Inertia::render('goals/milestones/Edit', [
            'goal' => $goal->only(['id', 'title']),
            'milestone' => $milestone->only(['id', 'title', 'notes', 'target_date', 'completed_at']),
        ]);
    }

    public function update(Request $request, Goal $goal, Milestone $milestone)
    {
        abort_unless($milestone->goal_id === $goal->id, 404);

        $validated = $request->validate([
            'title' => 'nullable|max:255',
            'notes' => 'nullable|max:1000',
            'target_date' => 'nullable|date',
            'completed' => 'nullable|boolean',
        ]);

        $data = array_filter([
            'title' => $validated['title'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'target_date' => $validated['target_date'] ?? null,
        ], fn ($v) => $v !== null);

        if (array_key_exists('completed', $validated)) {
            $data['completed_at'] = $validated['completed'] ? now() : null;
        }

        $milestone->update($data);

        return redirect()->route('goals.show', $goal)
            ->with('success', 'Milestone updated successfully!');
    }

    public function destroy($goalId, $id)
    {
        //
    }
}
