<script setup>
/**
 * AuthenticatedLayout.vue
 * 
 * Main layout shell for authenticated users.
 * Wrapped around all dashboard-level pages: Home, Portfolio, Analytics, etc.
 * Includes a sidebar for navigation, a topbar with user + theme toggle, and a main content area.
 */

import { useTheme } from '@/composables/useTheme'
import { Link, usePage } from '@inertiajs/vue3'
import route from 'ziggy-js'

const { theme, toggleTheme } = useTheme()
const user = usePage().props.auth.user
</script>

<template>
    <div class="min-h-screen flex bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100 transition-colors">

        <!-- Sidebar -->
        <aside class="w-64 bg-white dark:bg-gray-800 shadow hidden md:block">
            <div class="p-4 font-bold text-lg">FinBoard</div>

            <nav class="px-4 space-y-2 text-sm">
                <Link :href="route('dashboard')" class="block py-2 hover:underline">Dashboard</Link>
                <Link :href="route('portfolio')" class="block py-2 hover:underline">Portfolio</Link>
                <Link :href="route('transactions')" class="block py-2 hover:underline">Transactions</Link>
                <Link :href="route('analytics')" class="block py-2 hover:underline">Analytics</Link>
            </nav>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col">

            <!-- Topbar -->
            <header class="bg-gray-200 dark:bg-gray-800 p-4 flex justify-between items-center shadow">
                <h1 class="text-lg font-semibold">Welcome, {{ user.name }}</h1>

                <div class="flex gap-4 items-center">
                    <!-- Theme Toggle Button -->
                    <button @click="toggleTheme"
                        class="bg-gray-300 dark:bg-gray-700 px-3 py-1 rounded hover:bg-gray-400 dark:hover:bg-gray-600 transition">
                        {{ theme === 'dark' ? '🌙' : '☀️' }}
                    </button>

                    <!-- Logout Link -->
                    <Link :href="route('logout')" method="post" as="button"
                        class="text-sm text-red-600 hover:underline">
                    Logout
                    </Link>
                </div>
            </header>

            <!-- Main Content Slot -->
            <main class="p-6 flex-1">
                <slot />
            </main>
        </div>
    </div>
</template>
