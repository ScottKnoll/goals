<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
    class?: HTMLAttributes['class']
}>()
</script>

<template>
    <td data-slot="table-cell" :class="cn(
        'p-2 align-middle whitespace-nowrap [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-[2px] h-full',
        props.class,
    )
        ">
        <div class="flex min-h-10 w-full items-center">
            <slot />
        </div>
    </td>
</template>
