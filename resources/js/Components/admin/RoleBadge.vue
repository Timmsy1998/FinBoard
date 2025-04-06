<script setup>
defineProps({ role: String })
</script>

<template>
    <span :class="{
        'bg-green-100 text-green-800': role === 'user',
        'bg-yellow-100 text-yellow-800': role === 'admin',
        'bg-indigo-100 text-indigo-800': role === 'superuser'
    }" class="text-xs px-2 py-1 rounded-full font-medium">
        {{ role }}
    </span>
</template>
