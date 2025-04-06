<script setup>
import { ref, watch, computed } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import Modal from '@/Components/ui/Modal.vue'
import Input from '@/Components/ui/Input.vue'
import Select from '@/Components/ui/Select.vue'
import Button from '@/Components/ui/Button.vue'

const props = defineProps({
    user: Object,
})
const emit = defineEmits(['close', 'refresh'])

const currentUser = usePage().props.auth.user
const errors = usePage().props.errors

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'user',
})

watch(
    () => props.user,
    (user) => {
        if (user) {
            form.name = user.name
            form.email = user.email
            form.password = ''
            form.password_confirmation = ''
            form.role = user.role
        } else {
            form.reset()
        }
    },
    { immediate: true }
)

const shouldConfirmPassword = computed(() => form.password.length > 0)

const isEditing = computed(() => !!props.user)
const title = computed(() =>
    isEditing.value ? `Editing ${props.user.name}` : 'Create User'
)

function submit() {
    const action = isEditing.value
        ? form.put(route('users.update', props.user.id), {
            onSuccess: () => {
                emit('refresh')
                emit('close')
            }
        })
        : form.post(route('users.store'), {
            onSuccess: () => {
                emit('refresh')
                emit('close')
            }
        })
}
</script>

<template>
    <Modal :visible="true" :title="title" @close="emit('close')">
        <form @submit.prevent="submit" class="space-y-4">
            <Input v-model="form.name" label="Name" placeholder="Full name" required :error="errors.name" />

            <Input v-model="form.email" type="email" label="Email" placeholder="name@example.com" required
                :error="errors.email" />

            <Input v-model="form.password" type="password" label="Password"
                :placeholder="isEditing ? 'Leave blank to keep current password' : 'Choose a password'"
                :required="!isEditing" :error="errors.password" />

            <Input v-if="shouldConfirmPassword" v-model="form.password_confirmation" type="password"
                label="Confirm Password" placeholder="Repeat password" :required="!isEditing"
                :error="errors.password_confirmation" />


            <Select v-model="form.role" label="Role" :options="[
                { label: 'User', value: 'user' },
                ...(currentUser.role === 'superuser'
                    ? [{ label: 'Admin', value: 'admin' }]
                    : []),
            ]" :error="errors.role" />

            <div class="flex justify-end pt-4 space-x-2">
                <Button type="button" variant="secondary" @click="emit('close')">
                    Cancel
                </Button>
                <Button type="submit">
                    {{ isEditing ? 'Update' : 'Create' }}
                </Button>
            </div>
        </form>
    </Modal>
</template>
