<script setup>
defineProps({
    message: String, // Optional direct message
    error: String,   // Field name, e.g., 'email'
    errors: Object,  // Errors object from Inertia's form helper
})
</script>

<template>
    <div v-if="message || (errors && errors[error])" class="text-sm text-red-600 mt-1">
        {{ message || errors[error] }}
    </div>
</template>
