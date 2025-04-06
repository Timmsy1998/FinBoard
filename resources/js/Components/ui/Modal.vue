<script setup>
defineProps({
    title: String,
    visible: Boolean,
})
defineEmits(['close'])
</script>

<template>
    <transition name="fade">
        <div v-if="visible" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div
                class="bg-white dark:bg-gray-800 w-full max-w-lg mx-4 rounded shadow-lg p-6 transform transition-all duration-300 scale-95">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ title }}</h2>
                    <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700 dark:hover:text-white">
                        ✕
                    </button>
                </div>
                <slot />
            </div>
        </div>
    </transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
