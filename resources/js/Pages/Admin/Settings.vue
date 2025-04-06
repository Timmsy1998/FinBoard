<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import Input from '@/Components/ui/Input.vue'
import Button from '@/Components/ui/Button.vue'
import Select from '@/Components/ui/Select.vue'
import Toast from '@/Components/ui/Toast.vue'
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
            <Input label="Application Name" v-model="form.app_name" :error="form.errors.app_name" required />

            <Input label="ExchangeRate API Key" v-model="form.exchange_api_key" :error="form.errors.exchange_api_key"
                placeholder="Optional" />

            <Select label="Base Currency" v-model="form.base_currency" :options="[
                { label: 'USD', value: 'USD' },
                { label: 'EUR', value: 'EUR' },
                { label: 'GBP', value: 'GBP' },
                { label: 'INR', value: 'INR' },
                { label: 'JPY', value: 'JPY' },
            ]" :error="form.errors.base_currency" />

            <Select label="Timezone" v-model="form.timezone" :options="[
                { label: 'UTC', value: 'UTC' },
                { label: 'Europe/London', value: 'Europe/London' },
                { label: 'America/New_York', value: 'America/New_York' },
                { label: 'Asia/Kolkata', value: 'Asia/Kolkata' },
            ]" :error="form.errors.timezone" />

            <div class="pt-4">
                <Button type="submit" :disabled="form.processing">
                    Save Settings
                </Button>
            </div>
        </form>
    </div>
</template>