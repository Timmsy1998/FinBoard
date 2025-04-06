<script setup>
import { ref, watch, computed } from 'vue'
import Input from '@/Components/ui/Input.vue'
import Button from '@/Components/ui/Button.vue'

const props = defineProps({ modelValue: Object, isFirst: Boolean })
const emit = defineEmits(['update:modelValue', 'next', 'back'])

const local = ref({ ...props.modelValue })
watch(local, () => emit('update:modelValue', local.value), { deep: true })

const passwordMismatch = computed(() =>
    local.value.password && local.value.password_confirmation &&
    local.value.password !== local.value.password_confirmation
)
</script>

<template>
    <div class="space-y-6">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">👤 Superuser Setup</h2>

        <Input v-model="local.user_name" placeholder="Full Name" icon="mdi:account-outline" />
        <Input v-model="local.email" type="email" placeholder="Email" icon="mdi:email-outline" />
        <Input v-model="local.password" type="password" placeholder="Password" icon="mdi:lock-outline" />
        <Input v-model="local.password_confirmation" type="password" placeholder="Confirm Password"
            icon="mdi:lock-outline" />

        <p v-if="passwordMismatch" class="text-xs text-red-500">Passwords do not match.</p>

        <div class="flex justify-between pt-6">
            <Button v-if="!isFirst" @click="emit('back')" variant="secondary">← Back</Button>
            <Button @click="emit('next')" :disabled="passwordMismatch">Next →</Button>
        </div>
    </div>
</template>
