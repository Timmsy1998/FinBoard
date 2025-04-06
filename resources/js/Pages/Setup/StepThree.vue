<script setup>
import { ref, watch } from 'vue'
import Button from '@/Components/ui/Button.vue'

const props = defineProps({ modelValue: Object, isFirst: Boolean })
const emit = defineEmits(['update:modelValue', 'back', 'complete'])

const local = ref({ ...props.modelValue })
watch(local, () => emit('update:modelValue', local.value), { deep: true })

const logoPreview = ref(null)

if (local.value.logo && local.value.logo instanceof File) {
    const reader = new FileReader()
    reader.onload = () => (logoPreview.value = reader.result)
    reader.readAsDataURL(local.value.logo)
}
</script>

<template>
    <div class="space-y-6">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">✅ Confirm Setup</h2>

        <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
            <li><strong>App Name:</strong> {{ local.app_name }}</li>
            <li><strong>Base Currency:</strong> {{ local.base_currency }}</li>
            <li><strong>Exchange API Key:</strong> {{ local.api_key ? 'Provided' : 'Disabled' }}</li>
            <li><strong>Timezone:</strong> {{ local.timezone || 'UTC' }}</li>
            <li><strong>Superuser Email:</strong> {{ local.email }}</li>
        </ul>

        <div v-if="logoPreview" class="pt-4">
            <label class="text-sm font-medium text-gray-600 dark:text-gray-300 block mb-1">Logo Preview:</label>
            <img :src="logoPreview" class="h-16 rounded shadow" />
        </div>

        <div class="flex justify-between pt-6">
            <Button v-if="!isFirst" @click="emit('back')" variant="secondary">← Back</Button>
            <Button @click="emit('complete')" class="bg-green-600 hover:bg-green-700">🎉 Complete Setup</Button>
        </div>
    </div>
</template>
