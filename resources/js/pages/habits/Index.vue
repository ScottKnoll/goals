<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'
import { Button } from '@/components/ui/button'
import { type BreadcrumbItem } from '@/types'
import { Link } from '@inertiajs/vue3'

interface Habit {
    id: number
    title: string
}

defineProps<{
    habits: Habit[]
}>()

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Habits',
        href: '/habits',
    },
]
</script>

<template>

    <Head title="Habits" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Habits</h1>
                    <p class="text-muted-foreground">
                        Track and manage your daily habits to build better routines.
                    </p>
                </div>
                <Button as-child>
                    <Link :href="route('habits.create')">
                    Add Habit
                    </Link>
                </Button>
            </div>

            <div class="rounded-md border">
                <Table>
                    <TableCaption>A list of your habits.</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px]">Title</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead>Frequency</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="habit in habits" :key="habit.id">
                            <TableCell class="font-medium">
                                {{ habit.title }}
                            </TableCell>
                            <TableCell>Active</TableCell>
                            <TableCell>Daily</TableCell>
                            <TableCell class="text-right">
                                Edit
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>
