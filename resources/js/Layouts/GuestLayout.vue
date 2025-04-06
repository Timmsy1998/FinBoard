<script setup>
/**
 * GuestLayout.vue
 * 
 * This layout is used for guest-only pages like login and register.
 * It wraps the page in a centered container for clean forms or splash content.
 */

import { usePage } from '@inertiajs/vue3'
import Toast from '@/Components/ui/Toast.vue'
import { useTheme } from '@/composables/useTheme'
import { Icon } from '@iconify/vue'

const { theme, toggleTheme } = useTheme()
const appName = usePage().props.app?.name || 'FinBoard'
const logo = usePage().props.app?.logo
</script>

<template>
    <div
        class="min-h-screen flex flex-col justify-center items-center bg-gray-100 dark:bg-gradient-to-br dark:from-gray-900 dark:to-black transition-colors">
        <!-- Toggle on top -->
        <div class="absolute top-4 right-4">
            <button @click="toggleTheme" class="hover:scale-105 transition">
                <Icon :icon="theme === 'dark' ? 'mdi:weather-night' : 'mdi:weather-sunny'"
                    class="w-6 h-6 text-gray-600 dark:text-gray-300" />
            </button>
        </div>

        <div class="w-full max-w-md p-6 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-xl shadow-md">
            <img v-if="logo" :src="logo" class="h-10 object-contain" />
            <h1 v-else class="text-xl font-bold text-gray-800 dark:text-white">{{ appName }}</h1>
            <slot />
        </div>

        <Toast />
    </div>
</template>
