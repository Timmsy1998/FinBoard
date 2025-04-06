<script setup>
import Card from '@/Components/ui/Card.vue'
import Input from '@/Components/ui/Input.vue'
import TextArea from '@/Components/ui/TextArea.vue'
import Select from '@/Components/ui/Select.vue'
import Switch from '@/Components/ui/Switch.vue'
import Label from '@/Components/ui/Label.vue'
import FormError from '@/Components/ui/FormError.vue'
import Button from '@/Components/ui/Button.vue'

import { ref } from 'vue'

const name = ref('')
const email = ref('')
const message = ref('')
const role = ref('user')
const subscribe = ref(false)
const errors = {
    name: 'Name is required.',
    email: '',
}
</script>

<template>
    <div class="px-6 py-12 space-y-12 max-w-4xl mx-auto">
        <div>
            <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">🧩 Form Components</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">
                Showcasing form elements using your custom UI kit.
            </p>
        </div>

        <!-- Input Field -->
        <Card title="Input">
            <Input v-model="name" label="Full Name" placeholder="John Doe" :error="errors.name" required />
        </Card>

        <!-- Email Input -->
        <Card title="Email Input">
            <Input v-model="email" label="Email Address" placeholder="example@email.com" type="email"
                :error="errors.email" />
        </Card>

        <!-- Select Dropdown -->
        <Card title="Select">
            <Select v-model="role" label="Role" :options="[
                { label: 'User', value: 'user' },
                { label: 'Admin', value: 'admin' },
                { label: 'Superuser', value: 'superuser' },
            ]" />
        </Card>

        <!-- Switch -->
        <Card title="Switch">
            <Switch v-model="subscribe" label="Subscribe to newsletter" />
        </Card>

        <!-- TextArea -->
        <Card title="TextArea">
            <TextArea v-model="message" label="Message" placeholder="Enter your message..." />
        </Card>

        <!-- Form Error Example -->
        <Card title="FormError">
            <Label text="Sample Field with Error" />
            <FormError message="This field is required." />
        </Card>

        <!-- Submit Button -->
        <Card title="Button">
            <Button type="submit">Submit Form</Button>
        </Card>
    </div>
</template>
