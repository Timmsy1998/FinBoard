<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { useForm } from '@inertiajs/vue3'
import Input from '@/Components/ui/Input.vue'
import Label from '@/Components/ui/Label.vue'
import Button from '@/Components/ui/Button.vue'
import { router } from '@inertiajs/vue3'

defineOptions({ layout: GuestLayout })

const props = defineProps(['email', 'token'])

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
})
</script>

<template>
    <form @submit.prevent="form.post(route('password.update'))" class="space-y-6">
        <h1 class="text-xl font-semibold text-center">Reset Password</h1>

        <div>
            <Label for="email">Email</Label>
            <Input id="email" type="email" v-model="form.email" />
        </div>

        <div>
            <Label for="password">New Password</Label>
            <Input id="password" type="password" v-model="form.password" />
        </div>

        <div>
            <Label for="password_confirmation">Confirm Password</Label>
            <Input id="password_confirmation" type="password" v-model="form.password_confirmation" />
        </div>

        <Button class="w-full" variant="primary">Reset Password</Button>
    </form>
</template>
