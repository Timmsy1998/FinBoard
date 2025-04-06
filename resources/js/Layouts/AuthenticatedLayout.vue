<script setup>
import { computed } from 'vue'
import { useTheme } from '@/composables/useTheme'
import { useCurrency } from '@/composables/useCurrency'
import { usePage, Link } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import NavItem from '@/Components/ui/NavItem.vue'

const page = usePage()

// 🌙 Dark mode toggle
const { theme, toggleTheme } = useTheme()

// 💱 Currency selection
const { currency, setCurrency } = useCurrency()

// 👤 Authenticated user
const user = computed(() => page.props.auth?.user ?? null)

// 🏷️ App branding
const appName = computed(() => page.props.app?.name ?? 'FinBoard')
const appLogo = computed(() => page.props.app?.logo ?? null)
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-950 text-gray-800 dark:text-gray-100 transition-colors flex">
        <!-- Sidebar -->
        <aside
            class="w-64 hidden md:flex flex-col justify-between bg-white/80 dark:bg-gray-900/70 backdrop-blur-lg border-r border-gray-200 dark:border-gray-800 p-6 shadow-lg z-10">
            <!-- Branding -->
            <div>
                <div class="mb-8">
                    <img v-if="appLogo" :src="appLogo" alt="Logo" class="h-8 object-contain" />
                    <div v-else class="text-2xl font-semibold text-brand-600 dark:text-white tracking-tight">{{ appName
                        }}</div>
                </div>

                <!-- Navigation -->
                <nav class="space-y-4 text-sm">
                    <NavItem icon="mdi:view-dashboard-outline" label="Dashboard" :href="route('dashboard')" />
                    <NavItem icon="mdi:briefcase-outline" label="Portfolio" :href="route('portfolio')" />
                    <NavItem icon="mdi:swap-horizontal-bold" label="Transactions" :href="route('transactions')" />
                    <NavItem icon="mdi:chart-line" label="Analytics" :href="route('analytics')" />
                    <NavItem icon="mdi:account-multiple-outline" :label="'Users'" :href="route('users.index')"
                        v-if="user?.role === 'admin' || user?.role === 'superuser'" />

                </nav>
            </div>

            <!-- Footer -->
            <div class="text-xs text-gray-500 dark:text-gray-400">
                Logged in as {{ user?.name ?? 'Unknown' }}
            </div>
        </aside>

        <!-- Main -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Header -->
            <header
                class="flex items-center justify-between px-6 py-4 bg-white/80 dark:bg-gray-900/70 backdrop-blur border-b border-gray-200 dark:border-gray-800 shadow-sm z-10">
                <!-- Date -->
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                    <Icon icon="mdi:calendar-month-outline" class="w-5 h-5" />
                    <span>{{ new Date().toLocaleDateString() }}</span>
                </div>

                <!-- Currency Switcher -->
                <select v-model="currency"
                    class="bg-transparent text-sm text-gray-700 dark:text-gray-200 border-none focus:ring-0">
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="GBP">GBP</option>
                    <option value="JPY">JPY</option>
                    <option value="INR">INR</option>
                </select>

                <!-- Right Actions -->
                <div class="flex items-center gap-4">
                    <!-- Theme Toggle -->
                    <button @click="toggleTheme" class="hover:scale-105 transition">
                        <Icon :icon="theme === 'dark' ? 'mdi:weather-night' : 'mdi:weather-sunny'"
                            class="w-6 h-6 text-gray-700 dark:text-gray-200" />
                    </button>

                    <!-- User Menu -->
                    <div class="relative group">
                        <div
                            class="w-9 h-9 bg-brand-600 text-white rounded-full flex items-center justify-center font-semibold uppercase">
                            {{ user?.name?.charAt(0) ?? '?' }}
                        </div>
                        <div
                            class="absolute right-0 mt-2 hidden group-hover:block bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded shadow-md text-sm py-2 z-50">
                            <Link :href="route('logout')" method="post" as="button"
                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 w-full text-left">
                            Logout
                            </Link>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="px-6 py-10 flex-1">
                <slot />
            </main>
        </div>
    </div>
</template>
