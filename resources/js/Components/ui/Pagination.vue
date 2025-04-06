<script setup>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    links: Array,
    meta: Object
})

const filteredLinks = computed(() => {
    return props.links.filter(link => link.url !== null)
})
</script>

<template>
    <div v-if="meta" class="flex items-center justify-between pt-6">
        <div class="text-xs text-gray-500 dark:text-gray-400">
            Showing {{ meta.from }} to {{ meta.to }} of {{ meta.total }} results
        </div>

        <div class="flex items-center space-x-2">
            <Link v-for="(link, i) in filteredLinks" :key="i" :href="link.url" preserve-scroll
                class="px-3 py-1 text-sm rounded-md transition" :class="[
                    link.active
                        ? 'bg-blue-600 text-white'
                        : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700',
                    link.url ? 'cursor-pointer' : 'opacity-50 pointer-events-none'
                ]" v-html="link.label" />
        </div>
    </div>
</template>
