<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import Button from '@/Components/ui/Button.vue'
import { Icon } from '@iconify/vue'
import { router } from '@inertiajs/vue3'

function goHome() {
    router.visit('/')
}
</script>

<template>
    <GuestLayout>
        <div class="text-center py-20 space-y-6">
            <Icon icon="mdi:alert-circle-outline" class="text-blue-500 w-16 h-16 mx-auto" />
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">404 - Page Not Found</h1>
            <p class="text-gray-600 dark:text-gray-400">The page you are looking for doesn't exist or was moved.</p>
            <Button @click="goHome">Go Home</Button>
        </div>
    </GuestLayout>
</template>
