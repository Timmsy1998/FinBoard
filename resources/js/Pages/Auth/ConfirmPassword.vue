<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm } from '@inertiajs/vue3'
import Input from '@/Components/ui/Input.vue'
import Label from '@/Components/ui/Label.vue'
import Button from '@/Components/ui/Button.vue'

defineOptions({ layout: AuthenticatedLayout })

const form = useForm({ password: '' })
</script>

<template>
    <form @submit.prevent="form.post(route('password.confirm'))" class="space-y-6 max-w-md mx-auto">
        <h1 class="text-xl font-semibold text-center">Confirm Password</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 text-center">Please confirm your password to continue.</p>

        <div>
            <Label for="password">Password</Label>
            <Input id="password" type="password" v-model="form.password" />
        </div>

        <Button class="w-full" variant="primary">Confirm</Button>
    </form>
</template>
