<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { type BreadcrumbItem } from '@/types'
import { Edit, Calendar, Target, CheckCircle, Clock } from 'lucide-vue-next'

interface Goal {
    id: number
    title: string
    category: string
    start_date?: string
    end_date?: string
    notes?: string
    completed_at?: string
    milestones?: any[]
    habits?: any[]
    target_parameters?: any[]
    milestones_count?: number
    habits_count?: number
    target_parameters_count?: number
}

const props = defineProps<{
    goal: Goal
}>()

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Goals',
        href: '/goals',
    },
    {
        title: props.goal.title,
        href: `/goals/${props.goal.id}`,
    },
]

const getStatus = (goal: Goal) => {
    if (goal.completed_at) return 'completed'
    if (goal.end_date && new Date(goal.end_date) < new Date()) return 'overdue'
    if (goal.start_date && new Date(goal.start_date) <= new Date()) return 'in-progress'
    return 'planned'
}

const getStatusDisplay = (status: string) => {
    const statusConfig = {
        completed: { text: 'Completed', class: 'bg-green-100 text-green-800', icon: CheckCircle },
        'in-progress': { text: 'In Progress', class: 'bg-blue-100 text-blue-800', icon: Clock },
        planned: { text: 'Planned', class: 'bg-gray-100 text-gray-800', icon: Target },
        overdue: { text: 'Overdue', class: 'bg-red-100 text-red-800', icon: Clock },
    }
    return statusConfig[status as keyof typeof statusConfig] || statusConfig.planned
}

const getProgress = () => {
    const milestonesCount = props.goal.milestones_count || 0
    const completedMilestones = props.goal.milestones?.filter((m: any) => m.completed_at)?.length || 0
    return milestonesCount > 0 ? Math.round((completedMilestones / milestonesCount) * 100) : 0
}
</script>

<template>

    <Head :title="goal.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">{{ goal.title }}</h1>
                    <p class="text-muted-foreground">
                        {{ goal.category.charAt(0).toUpperCase() + goal.category.slice(1) }} Goal
                    </p>
                </div>
                <div class="flex space-x-2">
                    <Button as-child variant="outline">
                        <Link :href="route('goals.edit', goal.id)">
                        <Edit class="mr-2 h-4 w-4" />
                        Edit Goal
                        </Link>
                    </Button>
                    <Button as-child>
                        <Link :href="route('goals.index')">Back to Goals</Link>
                    </Button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <Card>
                        <CardHeader>
                            <CardTitle>Goal Details</CardTitle>
                            <CardDescription>
                                Overview and progress of your goal
                            </CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="flex items-center space-x-2">
                                <span
                                    :class="`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusDisplay(getStatus(goal)).class}`">
                                    <component :is="getStatusDisplay(getStatus(goal)).icon" class="mr-1 h-3 w-3" />
                                    {{ getStatusDisplay(getStatus(goal)).text }}
                                </span>
                            </div>

                            <div v-if="goal.notes" class="text-sm text-muted-foreground">
                                {{ goal.notes }}
                            </div>

                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span>Progress</span>
                                    <span>{{ getProgress() }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" :style="`width: ${getProgress()}%`"></div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Milestones</CardTitle>
                            <CardDescription>
                                Track your progress with milestones
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div v-if="goal.milestones && goal.milestones.length > 0" class="space-y-2">
                                <div v-for="milestone in goal.milestones" :key="milestone.id"
                                    class="flex items-center justify-between p-3 border rounded-lg">
                                    <span class="font-medium">{{ milestone.title }}</span>
                                    <span v-if="milestone.completed_at" class="text-green-600 text-sm">
                                        Completed
                                    </span>
                                </div>
                            </div>
                            <div v-else class="text-center py-4 text-muted-foreground">
                                No milestones yet. Add some to track your progress!
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <div class="space-y-6">
                    <Card>
                        <CardHeader>
                            <CardTitle>Timeline</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div v-if="goal.start_date" class="flex items-center space-x-2">
                                <Calendar class="h-4 w-4 text-muted-foreground" />
                                <div>
                                    <div class="text-sm font-medium">Start Date</div>
                                    <div class="text-sm text-muted-foreground">
                                        {{ new Date(goal.start_date).toLocaleDateString() }}
                                    </div>
                                </div>
                            </div>
                            <div v-if="goal.end_date" class="flex items-center space-x-2">
                                <Target class="h-4 w-4 text-muted-foreground" />
                                <div>
                                    <div class="text-sm font-medium">Target End Date</div>
                                    <div class="text-sm text-muted-foreground">
                                        {{ new Date(goal.end_date).toLocaleDateString() }}
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Statistics</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-sm text-muted-foreground">Milestones</span>
                                <span class="font-medium">{{ goal.milestones_count || 0 }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-muted-foreground">Related Habits</span>
                                <span class="font-medium">{{ goal.habits_count || 0 }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-muted-foreground">Target Parameters</span>
                                <span class="font-medium">{{ goal.target_parameters_count || 0 }}</span>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
