<script setup>
/**
 * GuestLayout.vue
 * 
 * This layout is used for guest-only pages like login and register.
 * It wraps the page in a centered container for clean forms or splash content.
 */

import { Head } from '@inertiajs/vue3'
</script>

<template>

    <Head title="Welcome" />

    <div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900 transition-colors">
        <div class="w-full max-w-md p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
            <slot />
        </div>
    </div>
</template>
