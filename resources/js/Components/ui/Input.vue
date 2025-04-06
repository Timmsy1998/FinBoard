<script setup>
import { Icon } from '@iconify/vue'
defineProps({
    modelValue: String,
    type: { type: String, default: 'text' },
    placeholder: String,
    id: String,
    name: String,
    icon: String,
    error: Boolean,
})
defineEmits(['update:modelValue'])
</script>

<template>
    <div class="relative">
        <div v-if="icon" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
            <Icon :icon="icon" class="w-5 h-5" />
        </div>
        <input :id="id" :name="name" :type="type" :placeholder="placeholder" :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)" :class="[
                'w-full py-2 pr-4 pl-10 rounded-md border transition',
                'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100',
                error
                    ? 'border-red-500 focus:ring-red-500'
                    : 'border-gray-300 dark:border-gray-600 focus:ring-blue-500',
            ]" />
    </div>
</template>
