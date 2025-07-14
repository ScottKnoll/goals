<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import { Form, FormField, FormLabel, FormControl, FormMessage } from '@/components/ui/form'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'

const form = useForm({
    title: '',
    notes: '',
    frequency: '',
    difficulty: '',
    current_streak: '',
    max_streak: '',
    last_completed_at: '',
})

defineOptions({
    layout: AppLayout,
})

function submit() {
    form.post(route('habits.store'))
}
</script>

<template>

    <Head title="Create Habit" />
    <div class="max-w-xl mx-auto mt-8">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">Create a New Habit</h1>
            <Button as-child variant="outline">
                <Link :href="route('habits.index')">Back</Link>
            </Button>
        </div>
        <Form @submit.prevent="submit">
            <FormField name="title">
                <FormLabel for="title">Title</FormLabel>
                <FormControl>
                    <Input id="title" v-model="form.title" required autofocus />
                </FormControl>
                <FormMessage v-if="form.errors.title">{{ form.errors.title }}</FormMessage>
            </FormField>
            <FormField name="notes">
                <FormLabel for="notes">Notes</FormLabel>
                <FormControl>
                    <Input id="notes" v-model="form.notes" />
                </FormControl>
                <FormMessage v-if="form.errors.notes">{{ form.errors.notes }}</FormMessage>
            </FormField>
            <FormField name="frequency">
                <FormLabel for="frequency">Frequency</FormLabel>
                <FormControl>
                    <Input id="frequency" v-model="form.frequency" required />
                </FormControl>
                <FormMessage v-if="form.errors.frequency">{{ form.errors.frequency }}</FormMessage>
            </FormField>
            <FormField name="difficulty">
                <FormLabel for="difficulty">Difficulty</FormLabel>
                <FormControl>
                    <Input id="difficulty" v-model="form.difficulty" required />
                </FormControl>
                <FormMessage v-if="form.errors.difficulty">{{ form.errors.difficulty }}</FormMessage>
            </FormField>
            <FormField name="current_streak">
                <FormLabel for="current_streak">Current Streak</FormLabel>
                <FormControl>
                    <Input id="current_streak" v-model="form.current_streak" type="number" min="0" />
                </FormControl>
                <FormMessage v-if="form.errors.current_streak">{{ form.errors.current_streak }}</FormMessage>
            </FormField>
            <FormField name="max_streak">
                <FormLabel for="max_streak">Max Streak</FormLabel>
                <FormControl>
                    <Input id="max_streak" v-model="form.max_streak" type="number" min="0" />
                </FormControl>
                <FormMessage v-if="form.errors.max_streak">{{ form.errors.max_streak }}</FormMessage>
            </FormField>
            <FormField name="last_completed_at">
                <FormLabel for="last_completed_at">Last Completed At</FormLabel>
                <FormControl>
                    <Input id="last_completed_at" v-model="form.last_completed_at" type="date" />
                </FormControl>
                <FormMessage v-if="form.errors.last_completed_at">{{ form.errors.last_completed_at }}</FormMessage>
            </FormField>
            <Button type="submit" :disabled="form.processing" class="mt-4">Create Habit</Button>
        </Form>
    </div>
</template>
