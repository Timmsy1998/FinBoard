<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { useForm } from '@inertiajs/vue3'
import Input from '@/Components/ui/Input.vue'
import Label from '@/Components/ui/Label.vue'
import Button from '@/Components/ui/Button.vue'
import FormError from '@/Components/ui/FormError.vue'

defineOptions({ layout: GuestLayout })

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})
</script>

<template>
    <div class="space-y-6 text-gray-800 dark:text-gray-100">
        <div class="text-center">
            <h1 class="text-3xl font-bold">Create Your Account</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Start managing your finances with FinBoard</p>
        </div>

        <form @submit.prevent="form.post(route('register'))" class="space-y-5">
            <!-- Name -->
            <div>
                <Label for="name">Full Name</Label>
                <Input id="name" name="name" v-model="form.name" icon="mdi:account-outline"
                    :error="!!form.errors.name" />
                <FormError :error="'name'" :errors="form.errors" />
            </div>

            <!-- Email -->
            <div>
                <Label for="email">Email Address</Label>
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

            <!-- Confirm -->
            <div>
                <Label for="password_confirmation">Confirm Password</Label>
                <Input id="password_confirmation" name="password_confirmation" type="password"
                    v-model="form.password_confirmation" icon="mdi:lock-check-outline" />
            </div>

            <Button :isLoading="form.processing" variant="primary">Register</Button>
        </form>

        <p class="text-center text-sm text-gray-600 dark:text-gray-400">
            Already have an account?
            <a :href="route('login')" class="text-blue-500 hover:underline">Log in</a>
        </p>
    </div>
</template>
