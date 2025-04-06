<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import Button from '@/Components/ui/Button.vue'
import { Icon } from '@iconify/vue'
import { router } from '@inertiajs/vue3'

function reloadPage() {
    location.reload()
}
</script>

<template>
    <GuestLayout>
        <div class="text-center py-20 space-y-6">
            <Icon icon="mdi:server" class="text-red-500 w-16 h-16 mx-auto" />
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">500 - Server Error</h1>
            <p class="text-gray-600 dark:text-gray-400">Something went wrong on our end. Please try again later.</p>
            <Button @click="reloadPage">Retry</Button>
        </div>
    </GuestLayout>
</template>
