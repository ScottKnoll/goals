<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'
import { Form, FormField, FormItem, FormLabel, FormControl, FormMessage } from '@/components/ui/form'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'

const formSchema = toTypedSchema(z.object({
    title: z.string().min(1, 'Title is required').max(255, 'Title must be less than 255 characters'),
    notes: z.string().optional(),
    frequency: z.string().min(1, 'Frequency is required').max(255, 'Frequency must be less than 255 characters'),
    difficulty: z.string().min(1, 'Difficulty is required').max(255, 'Difficulty must be less than 255 characters'),
    current_streak: z.coerce.number().min(0, 'Current streak must be 0 or greater').optional(),
    max_streak: z.coerce.number().min(0, 'Max streak must be 0 or greater').optional(),
    last_completed_at: z.string().optional(),
}))

const form = useForm({
    validationSchema: formSchema,
})

const onSubmit = form.handleSubmit((values) => {
    console.log('Form submitted!', values)
    import('@inertiajs/vue3').then(({ router }) => {
        console.log('About to post to:', route('habits.store'))
        router.post(route('habits.store'), values)
    })
})

console.log('Form errors:', form.errors.value)
console.log('Form is valid:', form.meta.value.valid)

defineOptions({
    layout: AppLayout,
})
</script>

<template>

    <Head title="Create Habit" />

    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Create a New Habit</h1>
                <p class="text-muted-foreground">
                    Add a new habit to track and build better routines.
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
                    Fill in the details for your new habit. You can always edit these later.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <form @submit="onSubmit" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <FormField v-slot="{ componentField }" name="title">
                            <FormItem>
                                <FormLabel>Title</FormLabel>
                                <FormControl>
                                    <Input v-bind="componentField" placeholder="Enter habit title" required autofocus />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <FormField v-slot="{ componentField }" name="frequency">
                            <FormItem>
                                <FormLabel>Frequency</FormLabel>
                                <FormControl>
                                    <Input v-bind="componentField" placeholder="e.g., Daily, Weekly" required />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>
                    </div>

                    <FormField v-slot="{ componentField }" name="notes">
                        <FormItem>
                            <FormLabel>Notes</FormLabel>
                            <FormControl>
                                <Input v-bind="componentField" placeholder="Optional notes about this habit" />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <FormField v-slot="{ componentField }" name="difficulty">
                            <FormItem>
                                <FormLabel>Difficulty</FormLabel>
                                <FormControl>
                                    <Input v-bind="componentField" placeholder="e.g., Easy, Medium, Hard" required />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <FormField v-slot="{ componentField }" name="last_completed_at">
                            <FormItem>
                                <FormLabel>Last Completed At</FormLabel>
                                <FormControl>
                                    <Input v-bind="componentField" type="date" />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <FormField v-slot="{ componentField }" name="current_streak">
                            <FormItem>
                                <FormLabel>Current Streak</FormLabel>
                                <FormControl>
                                    <Input v-bind="componentField" type="number" min="0" placeholder="0" />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <FormField v-slot="{ componentField }" name="max_streak">
                            <FormItem>
                                <FormLabel>Max Streak</FormLabel>
                                <FormControl>
                                    <Input v-bind="componentField" type="number" min="0" placeholder="0" />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>
                    </div>

                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <Button as-child variant="outline">
                            <Link :href="route('habits.index')">Cancel</Link>
                        </Button>
                        <Button type="submit">
                            Create Habit
                        </Button>
                    </div>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
