<script setup>
import { Icon } from '@iconify/vue'
import { Link, usePage } from '@inertiajs/vue3'

const props = defineProps({
    icon: String,
    label: String,
    href: String,
})

const current = usePage().url.startsWith(props.href)
</script>

<template>
    <Link :href="href" class="flex items-center gap-3 px-3 py-2 rounded-md transition font-medium" :class="[
        current
            ? 'bg-brand-50 dark:bg-gray-800 text-brand-600 dark:text-white'
            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800',
    ]">
    <Icon :icon="icon" class="w-5 h-5" />
    <span>{{ label }}</span>
    </Link>
</template>
