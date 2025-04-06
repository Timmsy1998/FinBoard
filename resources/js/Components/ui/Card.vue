<script setup>
defineProps({
    title: String,
})
</script>

<template>
    <div
        class="rounded-xl bg-white/70 dark:bg-gray-800/60 border border-gray-200 dark:border-gray-700 shadow-sm backdrop-blur-md p-5 space-y-3">
        <h3 v-if="title" class="text-base font-medium text-gray-800 dark:text-white">{{ title }}</h3>
        <slot />
    </div>
</template>
