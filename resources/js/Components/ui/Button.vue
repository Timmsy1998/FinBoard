<script setup>
defineProps({
    type: { type: String, default: 'submit' },
    variant: { type: String, default: 'primary' },
    isLoading: Boolean,
    disabled: Boolean,
})
</script>

<template>
    <button :type="type" :disabled="disabled || isLoading" :class="[
        'px-4 py-2 w-full rounded-md font-medium flex justify-center items-center gap-2 transition',
        'duration-200 ease-out transform active:scale-95',
        {
            'bg-blue-600 text-white hover:bg-blue-700': variant === 'primary',
            'bg-gray-300 text-gray-800 hover:bg-gray-400': variant === 'secondary',
            'bg-red-600 text-white hover:bg-red-700': variant === 'danger',
            'opacity-50 cursor-not-allowed': isLoading || disabled,
        },
    ]">
        <span v-if="isLoading" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full" />
        <slot />
    </button>
</template>
