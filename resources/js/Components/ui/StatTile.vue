<script setup>
import { Icon } from '@iconify/vue'

defineProps({
    label: String,
    value: String,
    icon: String,
    iconColor: { type: String, default: 'text-blue-500' },
})
</script>

<template>
    <div
        class="flex items-center gap-4 bg-white/80 dark:bg-gray-800/70 border border-gray-200 dark:border-gray-700 shadow-sm rounded-xl p-4">
        <div :class="['text-xl p-2 rounded-full bg-brand-50 dark:bg-brand-700/40', iconColor]">
            <Icon :icon="icon" class="w-6 h-6" />
        </div>
        <div>
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ label }}</p>
            <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ value }}</p>
        </div>
    </div>
</template>
