<script setup>
defineProps({
    modelValue: [String, Number],
    options: Array,
    id: String,
    name: String,
    error: Boolean,
})
defineEmits(['update:modelValue'])
</script>

<template>
    <select :id="id" :name="name" :value="modelValue" @change="$emit('update:modelValue', $event.target.value)" :class="[
        'w-full px-4 py-2 rounded-md border transition',
        'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100',
        error
            ? 'border-red-500 focus:ring-red-500'
            : 'border-gray-300 dark:border-gray-600 focus:ring-blue-500',
    ]">
        <option value="" disabled>Select an option</option>
        <option v-for="opt in options" :key="opt.value" :value="opt.value">
            {{ opt.label }}
        </option>
    </select>
</template>
