<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { useForm } from '@inertiajs/vue3'
import Input from '@/Components/ui/Input.vue'
import Label from '@/Components/ui/Label.vue'
import Button from '@/Components/ui/Button.vue'
import FormError from '@/Components/ui/FormError.vue'

defineOptions({ layout: GuestLayout })

const form = useForm({
    email: '',
    password: '',
    remember: false,
})
</script>

<template>
    <div class="space-y-6 text-gray-800 dark:text-gray-100">
        <div class="text-center">
            <h1 class="text-3xl font-bold">Welcome Back</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Log in to your FinBoard account</p>
        </div>

        <form @submit.prevent="form.post(route('login'))" class="space-y-5">
            <!-- Email -->
            <div>
                <Label for="email">Email</Label>
                <Input id="email" name="email" type="email" v-model="form.email" icon="mdi:email-outline"
                    :error="!!form.errors.email" />
                <FormError :error="'email'" :errors="form.errors" />
            </div>

            <!-- Password -->
            <div>
                <Label for="password">Password</Label>
                <Input id="password" name="password" type="password" v-model="form.password" icon="mdi:lock-outline"
                    :error="!!form.errors.password" />
                <FormError :error="'password'" :errors="form.errors" />
            </div>

            <!-- Remember + Forgot -->
            <div class="flex justify-between items-center text-sm">
                <label class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                    <input type="checkbox" v-model="form.remember" />
                    Remember me
                </label>
                <a :href="route('password.request')" class="text-blue-500 hover:underline">Forgot password?</a>
            </div>

            <!-- Submit -->
            <Button :isLoading="form.processing" variant="primary">Login</Button>
        </form>

        <!-- Register CTA -->
        <p class="text-center text-sm text-gray-600 dark:text-gray-400">
            Don’t have an account?
            <a :href="route('register')" class="text-blue-500 hover:underline">Create one</a>
        </p>
    </div>
</template>
