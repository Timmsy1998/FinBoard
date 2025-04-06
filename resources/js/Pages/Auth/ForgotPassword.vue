<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { useForm } from '@inertiajs/vue3'
import Input from '@/Components/ui/Input.vue'
import Label from '@/Components/ui/Label.vue'
import Button from '@/Components/ui/Button.vue'

defineOptions({ layout: GuestLayout })

const form = useForm({ email: '' })
</script>

<template>
    <form @submit.prevent="form.post(route('password.email'))" class="space-y-6">
        <h1 class="text-xl font-semibold text-center">Forgot your password?</h1>
        <p class="text-sm text-center text-gray-500 dark:text-gray-400">Enter your email and we’ll send a reset link.
        </p>

        <div>
            <Label for="email">Email</Label>
            <Input id="email" name="email" type="email" v-model="form.email" />
        </div>

        <Button class="w-full" variant="primary">Send Reset Link</Button>
    </form>
</template>
