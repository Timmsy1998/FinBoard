<script setup>
import { Icon } from '@iconify/vue'

defineProps({
    name: String,
    size: { type: String, default: '24' },
    className: { type: String, default: '' },
})
</script>

<template>
    <Icon :icon="name" :width="size" :height="size" :class="className" />
</template>
