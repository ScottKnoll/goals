<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Label } from '@/components/ui/label'

interface Goal {
    id: number
    title: string
    category: string
    start_date?: string
    end_date?: string
    notes?: string
    completed_at?: string
}

const props = defineProps<{
    goal: Goal
}>()

const form = useForm({
    title: props.goal.title,
    category: props.goal.category,
    start_date: props.goal.start_date ? props.goal.start_date.split('T')[0] : '',
    end_date: props.goal.end_date ? props.goal.end_date.split('T')[0] : '',
    notes: props.goal.notes || '',
})

const submit = () => {
    form.put(route('goals.update', props.goal.id))
}

defineOptions({
    layout: AppLayout,
})
</script>

<template>

    <Head title="Edit Goal" />

    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Edit Goal</h1>
                <p class="text-muted-foreground">
                    Update your goal details and track your progress.
                </p>
            </div>
            <Button as-child variant="outline">
                <Link :href="route('goals.index')">Back to Goals</Link>
            </Button>
        </div>

        <Card class="w-full">
            <CardHeader>
                <CardTitle>Goal Details</CardTitle>
                <CardDescription>
                    Update the details for your goal. Changes will be saved immediately.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <Label for="title">Title</Label>
                            <Input id="title" v-model="form.title" placeholder="Enter goal title" required autofocus />
                            <div v-if="form.errors.title" class="text-sm text-red-600">
                                {{ form.errors.title }}
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="category">Category</Label>
                            <select id="category" v-model="form.category"
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                required>
                                <option value="">Select category</option>
                                <option value="social">Social</option>
                                <option value="career-finance">Career & Finance</option>
                                <option value="health-fitness">Health & Fitness</option>
                                <option value="family">Family</option>
                                <option value="hobbies">Hobbies</option>
                                <option value="personal-development">Personal Development</option>
                                <option value="other">Other</option>
                            </select>
                            <div v-if="form.errors.category" class="text-sm text-red-600">
                                {{ form.errors.category }}
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <Label for="start_date">Start Date</Label>
                            <Input id="start_date" v-model="form.start_date" type="date" />
                            <div v-if="form.errors.start_date" class="text-sm text-red-600">
                                {{ form.errors.start_date }}
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="end_date">Target End Date</Label>
                            <Input id="end_date" v-model="form.end_date" type="date" />
                            <div v-if="form.errors.end_date" class="text-sm text-red-600">
                                {{ form.errors.end_date }}
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label for="notes">Notes</Label>
                        <textarea id="notes" v-model="form.notes"
                            class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Optional notes about this goal" rows="4"></textarea>
                        <div v-if="form.errors.notes" class="text-sm text-red-600">
                            {{ form.errors.notes }}
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <Button as-child variant="outline">
                            <Link :href="route('goals.index')">Cancel</Link>
                        </Button>
                        <Button type="submit" :disabled="form.processing">
                            Update Goal
                        </Button>
                    </div>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
