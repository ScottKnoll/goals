<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Label } from '@/components/ui/label'

interface Habit {
    id: number
    title: string
    notes?: string
    frequency: {
        type: string
        count: number
    }
    difficulty: 'trivial' | 'easy' | 'medium' | 'hard' | 'extreme'
    category?: string
}

const props = defineProps<{
    habit: Habit
}>()

const form = useForm({
    title: props.habit.title,
    notes: props.habit.notes || '',
    frequency: {
        type: props.habit.frequency.type,
        count: props.habit.frequency.count
    },
    difficulty: props.habit.difficulty,
    category: props.habit.category || '',
})

const submit = () => {
    form.put(route('habits.update', props.habit.id))
}

defineOptions({
    layout: AppLayout,
})
</script>

<template>

    <Head title="Edit Habit" />

    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Edit Habit</h1>
                <p class="text-muted-foreground">
                    Update your habit details and settings.
                </p>
            </div>
            <Button as-child variant="outline">
                <Link :href="route('habits.index')">Back to Habits</Link>
            </Button>
        </div>

        <Card class="w-full">
            <CardHeader>
                <CardTitle>Habit Details</CardTitle>
                <CardDescription>
                    Update the details for your habit. Changes will be saved immediately.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <Label for="title">Title</Label>
                            <Input id="title" v-model="form.title" placeholder="Enter habit title" required autofocus />
                            <div v-if="form.errors.title" class="text-sm text-red-600">
                                {{ form.errors.title }}
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="category">Category</Label>
                            <Input id="category" v-model="form.category" placeholder="e.g., Health, Work, Personal" />
                            <div v-if="form.errors.category" class="text-sm text-red-600">
                                {{ form.errors.category }}
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label for="notes">Notes</Label>
                        <Input id="notes" v-model="form.notes" placeholder="Optional notes about this habit" />
                        <div v-if="form.errors.notes" class="text-sm text-red-600">
                            {{ form.errors.notes }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <Label for="difficulty">Difficulty</Label>
                            <select id="difficulty" v-model="form.difficulty"
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                required>
                                <option value="">Select difficulty</option>
                                <option value="trivial">Trivial</option>
                                <option value="easy">Easy</option>
                                <option value="medium">Medium</option>
                                <option value="hard">Hard</option>
                                <option value="extreme">Extreme</option>
                            </select>
                            <div v-if="form.errors.difficulty" class="text-sm text-red-600">
                                {{ form.errors.difficulty }}
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="frequency_type">Frequency</Label>
                            <div class="flex gap-2">
                                <select id="frequency_type" v-model="form.frequency.type"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                    required>
                                    <option value="">Select frequency</option>
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                                <Input id="frequency_count" v-model="form.frequency.count" type="number" min="1"
                                    max="10" class="w-20" placeholder="1" />
                            </div>
                            <div v-if="form.errors['frequency.type']" class="text-sm text-red-600">
                                {{ form.errors['frequency.type'] }}
                            </div>
                            <div v-if="form.errors['frequency.count']" class="text-sm text-red-600">
                                {{ form.errors['frequency.count'] }}
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <Button as-child variant="outline">
                            <Link :href="route('habits.index')">Cancel</Link>
                        </Button>
                        <Button type="submit" :disabled="form.processing">
                            Update Habit
                        </Button>
                    </div>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
