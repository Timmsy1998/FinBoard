<script setup>
import { ref, onErrorCaptured, h } from 'vue'
import Button from '@/Components/ui/Button.vue'
import { Icon } from '@iconify/vue'

const error = ref(null)

onErrorCaptured((err) => {
    console.error('Captured error:', err)
    error.value = err
    return false // Allow parent to capture as well
})
</script>

<template>
    <div v-if="error"
        class="bg-red-50 dark:bg-red-900 border border-red-300 dark:border-red-700 p-6 rounded-lg text-center">
        <Icon icon="mdi:alert-circle-outline" class="w-10 h-10 text-red-500 mx-auto mb-2" />
        <h3 class="text-lg font-semibold text-red-600 dark:text-red-300">Something went wrong</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 mb-4">
            {{ error.message || 'An unexpected error occurred.' }}
        </p>
        <Button @click="() => location.reload()">Reload Page</Button>
    </div>
    <slot v-else />
</template>
