<script setup>
defineProps({
    for: String,
    required: Boolean,
    variant: {
        type: String,
        default: 'default',
    }
})
</script>

<template>
    <label :for="for" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        <slot />
        <span v-if="required" class="text-red-500 ml-1">*</span>
    </label>
</template>
