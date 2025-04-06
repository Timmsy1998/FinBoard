<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import Input from '@/Components/ui/Input.vue'
import Button from '@/Components/ui/Button.vue'
import Select from '@/Components/ui/Select.vue'
import Toast from '@/Components/ui/Toast.vue'
import Checkbox from '@/Components/ui/Switch.vue'
import Label from '@/Components/ui/Label.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { computed } from 'vue'

// Access props from the Inertia page
const { props } = usePage()
const settings = props.settings || {}
const form = useForm({
    app_name: settings.app_name,
    exchange_api_key: settings.exchange_api_key,
    base_currency: settings.base_currency,
    timezone: settings.timezone,
    demo_mode: settings.demo_mode === '1',
})

function save() {
    form.post(route('admin.settings.update'), {
        preserveScroll: true,
    })
}

// Optional: Track error state for inputs
const errors = computed(() => form.errors)
defineOptions({ layout: AuthenticatedLayout })
</script>

<template>
    <div class="px-6 pt-12 pb-24 space-y-10 max-w-2xl">
        <div>
            <h1 class="text-3xl font-light text-gray-900 dark:text-white">Admin Settings</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Configure global settings for FinBoard.
            </p>
        </div>

        <!-- 🔔 Toast -->
        <Toast v-if="$page.props.flash?.success" :message="$page.props.flash.success" />

        <form @submit.prevent="save" class="space-y-6">
            <Label for="app_name" required>Application Name</Label>
            <Input label="Application Name" v-model="form.app_name" :error="form.errors.app_name" required />

            <Label for="exchange_api_key">ExchangeRate API Key</Label>
            <Input label="ExchangeRate API Key" v-model="form.exchange_api_key" :error="form.errors.exchange_api_key"
                placeholder="Optional" />

            <Label for="base_currency" required>Base Currency</Label>

            <Select label="Base Currency" v-model="form.base_currency" :options="[
                { label: 'USD', value: 'USD' },
                { label: 'EUR', value: 'EUR' },
                { label: 'GBP', value: 'GBP' },
                { label: 'INR', value: 'INR' },
                { label: 'JPY', value: 'JPY' },
            ]" :error="form.errors.base_currency" />

            <Label for="timezone" required>Timezone</Label>
            <Select label="Timezone" v-model="form.timezone" :options="[
                { label: 'UTC', value: 'UTC' },
                { label: 'Europe/London', value: 'Europe/London' },
                { label: 'America/New_York', value: 'America/New_York' },
                { label: 'Asia/Kolkata', value: 'Asia/Kolkata' },
            ]" :error="form.errors.timezone" />

            <Checkbox v-model="form.demo_mode" label="Enable Demo Mode" />
            <Label for="demo_mode">Enable Demo Mode</Label>



            <div class="pt-4">
                <Button type="submit" :disabled="form.processing">
                    Save Settings
                </Button>
            </div>
        </form>
    </div>
</template>