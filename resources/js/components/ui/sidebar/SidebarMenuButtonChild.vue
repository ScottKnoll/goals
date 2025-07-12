<script setup>
import { cn } from '@/lib/utils'
import { Primitive } from 'reka-ui'
import { sidebarMenuButtonVariants } from '.'

const props = withDefaults(defineProps(), {
    as: 'button',
    variant: 'default',
    size: 'default',
})
</script>

<template>
    <Primitive data-slot="sidebar-menu-button" data-sidebar="menu-button" :data-size="size" :data-active="isActive"
        :class="cn(sidebarMenuButtonVariants({ variant, size }), props.class)" :as="as" :as-child="asChild"
        v-bind="$attrs">
        <slot />
    </Primitive>
</template>
