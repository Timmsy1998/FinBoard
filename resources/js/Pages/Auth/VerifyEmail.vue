<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm } from '@inertiajs/vue3'
import Button from '@/Components/ui/Button.vue'

defineOptions({ layout: AuthenticatedLayout })

const form = useForm()
</script>

<template>
    <div class="max-w-xl mx-auto text-center space-y-4">
        <h1 class="text-2xl font-bold">Email Verification</h1>
        <p>Please verify your email to continue. A verification link has been sent to your inbox.</p>

        <form @submit.prevent="form.post(route('verification.send'))">
            <Button variant="primary">Resend Verification Email</Button>
        </form>
    </div>
</template>
