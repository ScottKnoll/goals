<?php

use App\Http\Controllers\GoalController;
use App\Http\Controllers\GoalMilestoneController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\TaskController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::resource('habits', HabitController::class)->except('destroy');
    Route::resource('tasks', TaskController::class);
    Route::resource('goals', GoalController::class)->except('destroy');
    Route::resource('goals.milestones', GoalMilestoneController::class)->except('index', 'show');
    Route::resource('milestones.tasks', TaskController::class)->except(['index', 'show']);
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
