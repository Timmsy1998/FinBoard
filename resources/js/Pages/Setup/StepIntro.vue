<script setup>
const emit = defineEmits(['next'])
</script>

<template>
    <div class="text-center space-y-6">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white">👋 Welcome to FinBoard</h2>
        <p class="text-gray-600 dark:text-gray-400 max-w-xl mx-auto">
            We'll guide you through a quick setup wizard to configure your financial dashboard,
            including branding, currency settings, and your primary admin user.
        </p>

        <div class="pt-6">
            <button @click="emit('next')"
                class="px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 transition">
                Let’s Begin →
            </button>
        </div>
    </div>
</template>
