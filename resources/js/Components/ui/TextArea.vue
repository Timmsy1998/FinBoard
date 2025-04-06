<script setup>
defineProps({
    modelValue: String,
    id: String,
    name: String,
    placeholder: String,
    rows: { type: Number, default: 4 },
    error: Boolean,
})
defineEmits(['update:modelValue'])
</script>

<template>
    <textarea :id="id" :name="name" :placeholder="placeholder" :rows="rows" :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)" :class="[
            'w-full px-4 py-2 border rounded-md resize-none transition',
            'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100',
            error
                ? 'border-red-500 focus:ring-red-500'
                : 'border-gray-300 dark:border-gray-600 focus:ring-blue-500',
        ]" />
</template>
