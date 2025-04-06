<script setup>
import Input from '@/Components/ui/Input.vue'
import Select from '@/Components/ui/Select.vue'
import Switch from '@/Components/ui/Switch.vue'
import Button from '@/Components/ui/Button.vue'
import { ref } from 'vue'

const props = defineProps({ modelValue: Object })
const emit = defineEmits(['update:modelValue', 'next'])

function update(field, value) {
    emit('update:modelValue', { ...props.modelValue, [field]: value })
}

const logoPreview = ref(null)

function handleLogoUpload(e) {
    const file = e.target.files[0]
    if (!file) return
    update('logo', file)

    const reader = new FileReader()
    reader.onload = () => (logoPreview.value = reader.result)
    reader.readAsDataURL(file)
}
</script>

<template>
    <div class="space-y-6">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">📋 Board Settings</h2>

        <Input :modelValue="modelValue.app_name" @update:modelValue="val => update('app_name', val)"
            placeholder="App Name" icon="mdi:application" />

        <Select label="Base Currency" :modelValue="modelValue.base_currency"
            @update:modelValue="val => update('base_currency', val)" :options="[
                { value: 'USD', label: 'USD - US Dollar' },
                { value: 'EUR', label: 'EUR - Euro' },
                { value: 'GBP', label: 'GBP - British Pound' },
                { value: 'JPY', label: 'JPY - Yen' },
                { value: 'INR', label: 'INR - Rupee' }
            ]" />

        <div>
            <label class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-1 block">
                Use Currency Exchange API?
            </label>
            <Switch :modelValue="modelValue.api_key_enabled"
                @update:modelValue="val => update('api_key_enabled', val)" />

            <div v-if="modelValue.api_key_enabled" class="mt-4">
                <Input :modelValue="modelValue.api_key" @update:modelValue="val => update('api_key', val)"
                    placeholder="ExchangeRate.host API Key" icon="mdi:key-outline" />
            </div>
            <p v-else class="text-xs text-yellow-600 mt-2">
                Exchange features will be disabled.
            </p>
        </div>

        <div class="space-y-2">
            <label class="text-sm font-medium text-gray-600 dark:text-gray-300 block">Logo Upload</label>
            <input type="file" accept="image/*" @change="handleLogoUpload" />
            <div v-if="logoPreview" class="pt-2">
                <img :src="logoPreview" class="h-16 rounded shadow" />
            </div>
        </div>

        <div class="pt-6 flex justify-end">
            <Button @click="emit('next')">Next →</Button>
        </div>
    </div>
</template>
