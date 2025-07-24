<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'
import { Button } from '@/components/ui/button'
import { type BreadcrumbItem } from '@/types'
import { Link } from '@inertiajs/vue3'
import { valueUpdater } from '@/lib/utils'
import type {
    ColumnDef,
    SortingState,
} from '@tanstack/vue-table'
import {
    FlexRender,
    getCoreRowModel,
    getPaginationRowModel,
    getSortedRowModel,
    useVueTable,
} from '@tanstack/vue-table'
import { ArrowUpDown, MoreHorizontal, Edit, Trash2 } from 'lucide-vue-next'
import { h, ref } from 'vue'
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'

interface Habit {
    id: number
    title: string
    notes?: string
    frequency: {
        type: string
        count: number
    }
    difficulty: 'trivial' | 'easy' | 'medium' | 'hard' | 'extreme'
    current_streak: number
    max_streak: number
    last_completed_at?: string
}

const props = defineProps<{
    habits: Habit[]
}>()

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Habits',
        href: '/habits',
    },
]

const sorting = ref<SortingState>([])

const columns: ColumnDef<Habit>[] = [
    {
        accessorKey: 'title',
        header: ({ column }) => {
            return h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['Title', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
        },
        cell: ({ row }) => h('div', { class: 'font-medium pl-4' }, row.getValue('title')),
    },
    {
        accessorKey: 'difficulty',
        header: 'Difficulty',
        cell: ({ row }) => {
            const difficulty = row.getValue('difficulty') as string
            const difficultyColors = {
                trivial: 'bg-gray-100 text-gray-800',
                easy: 'bg-green-100 text-green-800',
                medium: 'bg-yellow-100 text-yellow-800',
                hard: 'bg-orange-100 text-orange-800',
                extreme: 'bg-red-100 text-red-800',
            }
            return h('span', {
                class: `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${difficultyColors[difficulty as keyof typeof difficultyColors]}`,
            }, difficulty.charAt(0).toUpperCase() + difficulty.slice(1))
        },
    },
    {
        accessorKey: 'frequency',
        header: 'Frequency',
        cell: ({ row }) => {
            const frequency = row.getValue('frequency') as { type: string; count: number }
            const count = frequency.count
            const type = frequency.type
            const display = count === 1 ? `1 time ${type}` : `${count} times ${type}`
            return h('div', { class: 'text-sm text-muted-foreground' }, display)
        },
    },
    {
        accessorKey: 'current_streak',
        header: ({ column }) => {
            return h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['Current Streak', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
        },
        cell: ({ row }) => {
            const streak = row.getValue('current_streak') as number
            return h('div', { class: 'text-left font-medium px-4' }, streak)
        },
    },
    {
        accessorKey: 'max_streak',
        header: ({ column }) => {
            return h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['Best Streak', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
        },
        cell: ({ row }) => {
            const streak = row.getValue('max_streak') as number
            return h('div', { class: 'text-left font-medium px-4' }, streak)
        },
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }) => {
            const habit = row.original

            return h(DropdownMenu, {}, () => [
                h(DropdownMenuTrigger, { asChild: true }, () => [
                    h(Button, { variant: 'ghost', class: 'h-8 w-8 p-0' }, () => [
                        h('span', { class: 'sr-only' }, 'Open menu'),
                        h(MoreHorizontal, { class: 'h-4 w-4' }),
                    ]),
                ]),
                h(DropdownMenuContent, { align: 'end' }, () => [
                    h(DropdownMenuLabel, {}, 'Actions'),
                    h(DropdownMenuItem, {
                        onClick: () => {
                            window.location.href = `/habits/${habit.id}/edit`
                        },
                    }, () => [
                        h(Edit, { class: 'mr-2 h-4 w-4' }),
                        'Edit',
                    ]),
                    h(DropdownMenuSeparator),
                    h(DropdownMenuItem, {
                        onClick: () => {
                            if (confirm('Are you sure you want to delete this habit?')) {
                                console.log('Delete habit:', habit.id)
                            }
                        },
                        class: 'text-red-600',
                    }, () => [
                        h(Trash2, { class: 'mr-2 h-4 w-4' }),
                        'Delete',
                    ]),
                ]),
            ])
        },
    },
]

const table = useVueTable({
    get data() { return props.habits },
    columns,
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    getSortedRowModel: getSortedRowModel(),
    onSortingChange: updaterOrValue => valueUpdater(updaterOrValue, sorting),
    state: {
        get sorting() { return sorting.value },
    },
})
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
                    <TableHeader>
                        <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                            <TableHead v-for="header in headerGroup.headers" :key="header.id">
                                <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header"
                                    :props="header.getContext()" />
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <template v-if="table.getRowModel().rows?.length">
                            <TableRow v-for="row in table.getRowModel().rows" :key="row.id"
                                :data-state="row.getIsSelected() ? 'selected' : undefined">
                                <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                                    <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                                </TableCell>
                            </TableRow>
                        </template>
                        <template v-else>
                            <TableRow>
                                <TableCell :colspan="columns.length" class="h-24 text-center">
                                    No habits found.
                                </TableCell>
                            </TableRow>
                        </template>
                    </TableBody>
                </Table>
            </div>

            <div class="flex items-center justify-end space-x-2 py-4">
                <div class="flex-1 text-sm text-muted-foreground">
                    Showing {{ table.getFilteredRowModel().rows.length }} of {{ table.getFilteredRowModel().rows.length
                    }} habits.
                </div>
                <div class="space-x-2">
                    <Button variant="outline" size="sm" :disabled="!table.getCanPreviousPage()"
                        @click="table.previousPage()">
                        Previous
                    </Button>
                    <Button variant="outline" size="sm" :disabled="!table.getCanNextPage()" @click="table.nextPage()">
                        Next
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
