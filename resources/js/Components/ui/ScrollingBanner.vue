<template>
    <div class="relative overflow-hidden bg-yellow-100 dark:bg-yellow-900 border-b border-yellow-300 dark:border-yellow-700 text-yellow-900 dark:text-yellow-100"
        :class="wrapperClass">
        <div class="whitespace-nowrap animate-marquee px-4 py-2 text-sm font-medium">
            <slot>This is a scrolling alert banner.</slot>
        </div>
    </div>
</template>

<script setup>
// Props for optional class overrides
defineProps({
    wrapperClass: {
        type: String,
        default: '',
    },
})
</script>

<style scoped>
@keyframes marquee {
    0% {
        transform: translateX(100%);
    }

    100% {
        transform: translateX(-100%);
    }
}

.animate-marquee {
    display: inline-block;
    animation: marquee 15s linear infinite;
}
</style>