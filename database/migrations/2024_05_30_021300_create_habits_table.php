<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('habits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('notes')->nullable();
            $table->json('frequency');
            $table->enum('difficulty', ['trivial', 'easy', 'medium', 'hard', 'extreme'])->default('trivial');
            $table->string('category')->nullable();
            $table->integer('current_streak')->default(0);
            $table->integer('max_streak')->default(0);
            $table->timestamp('last_completed_at')->nullable();
            $table->timestamps();
        });

        $habits = [
            [
                'id' => 1,
                'user_id' => 1,
                'title' => 'Work Out',
                'notes' => 'Get those gains! 💪 Building strength and endurance one rep at a time.',
                'frequency' => json_encode(['type' => 'weekly', 'count' => 3]),
                'difficulty' => 'medium',
                'category' => 'Health',
                'current_streak' => 0,
                'max_streak' => 0,
            ],
            [
                'id' => 2,
                'user_id' => 1,
                'title' => 'Coding',
                'notes' => 'Level up those programming skills! 🚀 Building the future, one commit at a time.',
                'frequency' => json_encode(['type' => 'daily', 'count' => 1]),
                'difficulty' => 'hard',
                'category' => 'Work',
                'current_streak' => 0,
                'max_streak' => 0,
            ],
            [
                'id' => 3,
                'user_id' => 1,
                'title' => 'Meditate',
                'notes' => 'Find your inner peace 🧘‍♂️ Because a calm mind is a powerful mind.',
                'frequency' => json_encode(['type' => 'daily', 'count' => 1]),
                'difficulty' => 'easy',
                'category' => 'Wellness',
                'current_streak' => 0,
                'max_streak' => 0,
            ],
            [
                'id' => 4,
                'user_id' => 1,
                'title' => 'Log Habits',
                'notes' => 'Track your progress! 📊 Because what gets measured gets improved.',
                'frequency' => json_encode(['type' => 'daily', 'count' => 1]),
                'difficulty' => 'trivial',
                'category' => 'Productivity',
                'current_streak' => 0,
                'max_streak' => 0,
            ],
            [
                'id' => 5,
                'user_id' => 1,
                'title' => 'Floss',
                'notes' => 'Keep those pearly whites healthy! 😁 Your future self will thank you.',
                'frequency' => json_encode(['type' => 'daily', 'count' => 2]),
                'difficulty' => 'easy',
                'category' => 'Health',
                'current_streak' => 0,
                'max_streak' => 0,
            ],
            [
                'id' => 6,
                'user_id' => 1,
                'title' => 'Wear Retainer',
                'notes' => 'Maintain that perfect smile! 😬 Because braces were expensive.',
                'frequency' => json_encode(['type' => 'monthly', 'count' => 4]),
                'difficulty' => 'easy',
                'category' => 'Health',
                'current_streak' => 0,
                'max_streak' => 0,
            ],
            [
                'id' => 7,
                'user_id' => 1,
                'title' => 'No Weed',
                'notes' => 'Stay clear-headed and focused! 🧠 Your brain deserves the best.',
                'frequency' => json_encode(['type' => 'daily', 'count' => 1]),
                'difficulty' => 'extreme',
                'category' => 'Wellness',
                'current_streak' => 0,
                'max_streak' => 0,
            ],
            [
                'id' => 8,
                'user_id' => 1,
                'title' => 'No Caffeine',
                'notes' => 'Break free from the bean! ☕ Natural energy is the best energy.',
                'frequency' => json_encode(['type' => 'daily', 'count' => 1]),
                'difficulty' => 'hard',
                'category' => 'Wellness',
                'current_streak' => 0,
                'max_streak' => 0,
            ],
            [
                'id' => 9,
                'user_id' => 1,
                'title' => 'No Nicotine',
                'notes' => 'Breathe easy! 💨 Your lungs will thank you for this one.',
                'frequency' => json_encode(['type' => 'daily', 'count' => 1]),
                'difficulty' => 'extreme',
                'category' => 'Health',
                'current_streak' => 0,
                'max_streak' => 0,
            ],
            [
                'id' => 10,
                'user_id' => 1,
                'title' => 'Read Books',
                'notes' => 'Expand your mind! 📚 Knowledge is power, and books are the gateway.',
                'frequency' => json_encode(['type' => 'weekly', 'count' => 2]),
                'difficulty' => 'medium',
                'category' => 'Personal',
                'current_streak' => 0,
                'max_streak' => 0,
            ],
            [
                'id' => 11,
                'user_id' => 1,
                'title' => 'Dental Cleaning',
                'notes' => 'Keep that smile bright! 🦷 Professional cleaning for optimal oral health.',
                'frequency' => json_encode(['type' => 'yearly', 'count' => 2]),
                'difficulty' => 'easy',
                'category' => 'Health',
                'current_streak' => 0,
                'max_streak' => 0,
            ],
            [
                'id' => 12,
                'user_id' => 1,
                'title' => 'Call Family',
                'notes' => 'Stay connected! 📞 Family bonds are precious and need nurturing.',
                'frequency' => json_encode(['type' => 'monthly', 'count' => 1]),
                'difficulty' => 'easy',
                'category' => 'Relationships',
                'current_streak' => 0,
                'max_streak' => 0,
            ],
        ];

        \App\Models\Habit::fillAndInsert($habits);

        DB::statement("ALTER TABLE habits AUTO_INCREMENT = " . (count($habits) + 1));

        Schema::create('habit_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('habit_id')->constrained()->onDelete('cascade');
            $table->date('completed_on');
            $table->timestamps();

            $table->unique(['habit_id', 'completed_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('habits');
    }
};
