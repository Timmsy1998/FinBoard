<script setup>
defineProps({ for: String })
</script>

<template>
    <label :for="for" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        <slot />
    </label>
</template>
