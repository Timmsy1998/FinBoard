<script setup>
import SkeletonWidget from './SkeletonWidget.vue'

defineProps({
    loading: Boolean
})
</script>

<template>
    <SkeletonWidget v-if="loading" />
    <div v-else class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
        <slot />
    </div>
</template>
