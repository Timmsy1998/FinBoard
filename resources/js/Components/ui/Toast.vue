<script setup>
import { ref, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'

const flash = usePage().props?.flash || {}
const visible = ref(false)

onMounted(() => {
    if (flash.success || flash.error) {
        visible.value = true
        setTimeout(() => {
            visible.value = false
        }, 4000)
    }
})
</script>

<template>
    <div v-if="flash.success || flash.error" class="fixed bottom-4 right-4 z-50">
        <div v-if="flash.success" class="bg-green-500 text-white px-4 py-2 rounded shadow">
            {{ flash.success }}
        </div>
        <div v-else-if="flash.error" class="bg-red-500 text-white px-4 py-2 rounded shadow">
            {{ flash.error }}
        </div>
    </div>
</template>
