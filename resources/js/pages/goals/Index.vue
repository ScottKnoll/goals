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
import { ArrowUpDown, MoreHorizontal, Edit, Trash2, Plus, Search, Filter } from 'lucide-vue-next'
import { h, ref } from 'vue'
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { Input } from '@/components/ui/input'

interface Goal {
    id: number
    title: string
    category: string
    start_date?: string
    end_date?: string
    notes?: string
    completed_at?: string
    milestones_count?: number
    habits_count?: number
    target_parameters_count?: number
    milestones?: any[]
    habits?: any[]
    target_parameters?: any[]
}

const props = defineProps<{
    goals: Goal[]
}>()

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Goals',
        href: '/goals',
    },
]

const sorting = ref<SortingState>([])
const searchQuery = ref('')

const getStatus = (goal: Goal) => {
    if (goal.completed_at) return 'completed'
    if (goal.end_date && new Date(goal.end_date) < new Date()) return 'overdue'
    if (goal.start_date && new Date(goal.start_date) <= new Date()) return 'in-progress'
    return 'planned'
}

const getStatusDisplay = (status: string) => {
    const statusConfig = {
        completed: { text: 'Completed', class: 'bg-green-100 text-green-800', icon: '✓' },
        'in-progress': { text: 'In Progress', class: 'bg-blue-100 text-blue-800', icon: '⏳' },
        planned: { text: 'Planned', class: 'bg-gray-100 text-gray-800', icon: '📋' },
        overdue: { text: 'Overdue', class: 'bg-red-100 text-red-800', icon: '⚠' },
    }
    return statusConfig[status as keyof typeof statusConfig] || statusConfig.planned
}

const getPriority = (goal: Goal) => {
    if (!goal.end_date) return 'medium'
    const daysUntilEnd = Math.ceil((new Date(goal.end_date).getTime() - new Date().getTime()) / (1000 * 60 * 60 * 24))
    if (daysUntilEnd <= 7) return 'high'
    if (daysUntilEnd <= 30) return 'medium'
    return 'low'
}

const getPriorityDisplay = (priority: string) => {
    const priorityConfig = {
        high: { text: 'High', class: 'bg-red-100 text-red-800', icon: '↑' },
        medium: { text: 'Medium', class: 'bg-yellow-100 text-yellow-800', icon: '→' },
        low: { text: 'Low', class: 'bg-green-100 text-green-800', icon: '↓' },
    }
    return priorityConfig[priority as keyof typeof priorityConfig] || priorityConfig.medium
}

const columns: ColumnDef<Goal>[] = [
    {
        accessorKey: 'title',
        header: ({ column }) => {
            return h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['Title', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
        },
        cell: ({ row }) => {
            const goal = row.original
            return h(Link, {
                href: `/goals/${goal.id}`,
                class: 'font-medium pl-4 hover:underline text-blue-600'
            }, () => goal.title)
        },
    },
    {
        accessorKey: 'category',
        header: 'Category',
        cell: ({ row }) => {
            const category = row.getValue('category') as string
            return h('span', {
                class: 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800',
            }, category.charAt(0).toUpperCase() + category.slice(1))
        },
    },
    {
        accessorKey: 'status',
        header: ({ column }) => {
            return h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['Status', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
        },
        cell: ({ row }) => {
            const goal = row.original
            const status = getStatus(goal)
            const statusDisplay = getStatusDisplay(status)
            return h('span', {
                class: `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusDisplay.class}`,
            }, () => [statusDisplay.icon + ' ' + statusDisplay.text])
        },
    },
    {
        accessorKey: 'priority',
        header: ({ column }) => {
            return h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['Priority', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
        },
        cell: ({ row }) => {
            const goal = row.original
            const priority = getPriority(goal)
            const priorityDisplay = getPriorityDisplay(priority)
            return h('span', {
                class: `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${priorityDisplay.class}`,
            }, () => [priorityDisplay.icon + ' ' + priorityDisplay.text])
        },
    },
    {
        accessorKey: 'progress',
        header: 'Progress',
        cell: ({ row }) => {
            const goal = row.original
            const milestonesCount = goal.milestones_count || 0
            const completedMilestones = goal.milestones?.filter((m: any) => m.completed_at)?.length || 0
            const progress = milestonesCount > 0 ? Math.round((completedMilestones / milestonesCount) * 100) : 0

            return h('div', { class: 'flex items-center space-x-2' }, [
                h('div', { class: 'w-full bg-gray-200 rounded-full h-2' }, [
                    h('div', {
                        class: 'bg-blue-600 h-2 rounded-full',
                        style: `width: ${progress}%`
                    })
                ]),
                h('span', { class: 'text-sm text-gray-600 min-w-[3rem]' }, `${progress}%`)
            ])
        },
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }) => {
            const goal = row.original

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
                            window.location.href = `/goals/${goal.id}/edit`
                        },
                    }, () => [
                        h(Edit, { class: 'mr-2 h-4 w-4' }),
                        'Edit',
                    ]),
                    h(DropdownMenuSeparator),
                    h(DropdownMenuItem, {
                        onClick: () => {
                            if (confirm('Are you sure you want to delete this goal?')) {
                                console.log('Delete goal:', goal.id)
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
    get data() {
        return props.goals.filter(goal =>
            goal.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            goal.category.toLowerCase().includes(searchQuery.value.toLowerCase())
        )
    },
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

    <Head title="Goals" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Goals</h1>
                    <p class="text-muted-foreground">
                        Track and manage your personal goals below.
                    </p>
                </div>
                <Button as-child>
                    <Link :href="route('goals.create')">
                    <Plus class="mr-2 h-4 w-4" />
                    Add Goal
                    </Link>
                </Button>
            </div>

            <div class="flex items-center space-x-2">
                <div class="relative flex-1 max-w-sm">
                    <Search class="absolute left-2 top-2.5 h-4 w-4 text-muted-foreground" />
                    <Input v-model="searchQuery" placeholder="Filter goals..." class="pl-8" />
                </div>
                <Button variant="outline" size="sm">
                    <Filter class="mr-2 h-4 w-4" />
                    Status
                </Button>
                <Button variant="outline" size="sm">
                    <Filter class="mr-2 h-4 w-4" />
                    Priority
                </Button>
                <Button variant="outline" size="sm">
                    <Filter class="mr-2 h-4 w-4" />
                    View
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
                                    No goals found.
                                </TableCell>
                            </TableRow>
                        </template>
                    </TableBody>
                </Table>
            </div>

            <div class="flex items-center justify-between space-x-2 py-4">
                <div class="flex-1 text-sm text-muted-foreground">
                    {{ table.getFilteredRowModel().rows.length }} of {{ table.getFilteredRowModel().rows.length }}
                    goal(s) selected.
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
