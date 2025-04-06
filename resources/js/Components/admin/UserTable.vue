<script setup>
import { ref } from 'vue'
import Button from '@/Components/ui/Button.vue'
import RoleBadge from './RoleBadge.vue'

defineProps({ users: Array })
const emit = defineEmits(['edit', 'delete'])
</script>

<template>
    <div class="rounded-xl shadow bg-white dark:bg-gray-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 dark:bg-gray-700 text-left text-xs uppercase text-gray-500 dark:text-gray-300">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.id" class="border-t dark:border-gray-700">
                    <td class="px-4 py-3">{{ user.name }}</td>
                    <td class="px-4 py-3">{{ user.email }}</td>
                    <td class="px-4 py-3">
                        <RoleBadge :role="user.role" />
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <Button @click="$emit('edit', user)" small>Edit</Button>
                        <Button @click="$emit('delete', user)" variant="danger" small>Delete</Button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
