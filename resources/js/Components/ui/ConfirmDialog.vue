<script setup>
import { ref, watch } from 'vue'
import Modal from '@/Components/ui/Modal.vue'
import Button from '@/Components/ui/Button.vue'

const props = defineProps({
    title: {
        type: String,
        default: 'Are you sure?',
    },
    message: {
        type: String,
        default: 'This action cannot be undone.',
    },
    confirmText: {
        type: String,
        default: 'Confirm',
    },
    cancelText: {
        type: String,
        default: 'Cancel',
    },
    danger: {
        type: Boolean,
        default: true,
    },
})

const emit = defineEmits(['confirm', 'cancel'])
const visible = ref(true)

function close() {
    visible.value = false
    emit('cancel')
}

function confirm() {
    visible.value = false
    emit('confirm')
}
</script>

<template>
    <Modal :visible="visible" :title="props.title" @close="close">
        <div class="space-y-4">
            <p class="text-sm text-gray-700 dark:text-gray-300">
                {{ props.message }}
            </p>

            <div class="flex justify-end space-x-3 pt-2">
                <Button variant="secondary" @click="close">{{ cancelText }}</Button>
                <Button :variant="danger ? 'danger' : 'primary'" @click="confirm">
                    {{ confirmText }}
                </Button>
            </div>
        </div>
    </Modal>
</template>
