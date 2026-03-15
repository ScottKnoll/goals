<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Label } from '@/components/ui/label'
import { type BreadcrumbItem } from '@/types'

interface Goal {
    id: number
    title: string
}

interface Milestone {
    id: number
    title: string
    notes?: string
    target_date?: string
    completed_at?: string
}

const props = defineProps<{
    goal: Goal
    milestone: Milestone
}>()

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Goals', href: '/goals' },
    { title: props.goal.title, href: `/goals/${props.goal.id}` },
    { title: 'Edit Milestone', href: route('goals.milestones.edit', [props.goal.id, props.milestone.id]) },
]

const form = useForm({
    title: props.milestone.title,
    notes: props.milestone.notes ?? '',
    target_date: props.milestone.target_date
        ? props.milestone.target_date.split('T')[0]
        : '',
    completed: !!props.milestone.completed_at,
})

const submit = () => {
    form.put(route('goals.milestones.update', [props.goal.id, props.milestone.id]))
}
</script>

<template>

    <Head title="Edit Milestone" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Edit Milestone</h1>
                    <p class="text-muted-foreground">
                        Update milestone for "{{ goal.title }}"
                    </p>
                </div>
                <Button as-child variant="outline">
                    <Link :href="route('goals.show', goal.id)">Back to Goal</Link>
                </Button>
            </div>

            <Card class="w-full">
                <CardHeader>
                    <CardTitle>Milestone Details</CardTitle>
                    <CardDescription>
                        Update the milestone details and completion status.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="space-y-2">
                            <Label for="title">Title</Label>
                            <Input
                                id="title"
                                v-model="form.title"
                                placeholder="e.g. Complete first draft"
                                required
                                autofocus
                            />
                            <p v-if="form.errors.title" class="text-sm text-red-600">
                                {{ form.errors.title }}
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label for="target_date">Target Date</Label>
                            <Input id="target_date" v-model="form.target_date" type="date" />
                            <p v-if="form.errors.target_date" class="text-sm text-red-600">
                                {{ form.errors.target_date }}
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label for="notes">Notes</Label>
                            <textarea
                                id="notes"
                                v-model="form.notes"
                                class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                placeholder="Optional notes for this milestone"
                                rows="4"
                            />
                            <p v-if="form.errors.notes" class="text-sm text-red-600">
                                {{ form.errors.notes }}
                            </p>
                        </div>

                        <div class="flex items-center space-x-2">
                            <input
                                id="completed"
                                v-model="form.completed"
                                type="checkbox"
                                class="h-4 w-4 rounded border-input"
                            />
                            <Label for="completed" class="font-normal">Mark as completed</Label>
                        </div>

                        <div class="flex justify-end space-x-4 border-t pt-6">
                            <Button as-child variant="outline">
                                <Link :href="route('goals.show', goal.id)">Cancel</Link>
                            </Button>
                            <Button type="submit" :disabled="form.processing">
                                Update Milestone
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
