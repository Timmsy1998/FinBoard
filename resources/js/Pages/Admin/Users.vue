<script setup>
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Input from '@/Components/ui/Input.vue'
import Select from '@/Components/ui/Select.vue'
import Button from '@/Components/ui/Button.vue'
import ConfirmDialog from '@/Components/ui/ConfirmDialog.vue'
import RoleBadge from '@/Components/admin/RoleBadge.vue'
import UserForm from '@/Components/admin/UserForm.vue'
import Pagination from '@/Components/ui/Pagination.vue'
import { Icon } from '@iconify/vue'

defineOptions({ layout: AuthenticatedLayout })

const page = usePage()
const users = computed(() => page.props.users)
const currentUser = computed(() => page.props.auth.user)

const showForm = ref(false)
const selectedUser = ref(null)
const confirmDelete = ref(null)

const query = ref({
    search: page.props.search || '',
    role: page.props.role || '',
    sort: page.props.sort || 'name',
    direction: page.props.direction || 'asc',
})

const selectedIds = ref([])

function fetch() {
    router.get(route('users.index'), query.value, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    })
}

function newUser() {
    selectedUser.value = null
    showForm.value = true
}

function editUser(user) {
    selectedUser.value = user
    showForm.value = true
}

function toggleSort(field) {
    if (query.value.sort === field) {
        query.value.direction = query.value.direction === 'asc' ? 'desc' : 'asc'
    } else {
        query.value.sort = field
        query.value.direction = 'asc'
    }
    fetch()
}

function removeUser(user) {
    confirmDelete.value = user
}

function confirmRemove() {
    router.delete(route('users.destroy', confirmDelete.value.id), {
        preserveScroll: true,
        onFinish: () => {
            confirmDelete.value = null
            fetch()
        },
    })
}

function bulkDelete() {
    if (selectedIds.value.length === 0) return
    confirmDelete.value = { id: selectedIds.value, bulk: true }
}

function confirmBulkRemove() {
    router.post(route('users.bulkDelete'), { ids: selectedIds.value }, {
        preserveScroll: true,
        onFinish: () => {
            selectedIds.value = []
            confirmDelete.value = null
            fetch()
        },
    })
}
</script>

<template>
    <div class="px-6 pt-12 pb-24 space-y-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-light text-gray-900 dark:text-white">User Management</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage users, roles, and permissions.</p>
            </div>
            <Button @click="newUser">+ New User</Button>
        </div>

        <!-- Filters -->
        <div class="grid sm:grid-cols-3 gap-4">
            <Input v-model="query.search" icon="mdi:magnify" placeholder="Search users..." @input="fetch" />
            <Select v-model="query.role" @change="fetch" :options="[
                { value: '', label: 'All Roles' },
                { value: 'user', label: 'User' },
                { value: 'admin', label: 'Admin' },
                { value: 'superuser', label: 'Superuser' },
            ]" />
        </div>

        <!-- Bulk Actions -->
        <div v-if="selectedIds.length > 0"
            class="bg-yellow-100 dark:bg-yellow-900 border rounded p-4 flex justify-between items-center text-sm mt-4">
            <span>{{ selectedIds.length }} selected</span>
            <Button size="sm" variant="danger" @click="bulkDelete">Delete Selected</Button>
        </div>

        <!-- User Table -->
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded shadow">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700 text-left text-xs text-gray-600 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-3">
                            <input type="checkbox"
                                @change="e => selectedIds = e.target.checked ? users.data.map(u => u.id) : []"
                                :checked="selectedIds.length === users.data.length" />
                        </th>
                        <th class="px-4 py-3 cursor-pointer" @click="toggleSort('name')">
                            Name
                            <Icon :icon="query.sort === 'name' ? 'mdi:arrow-up-down' : 'mdi:swap-vertical'"
                                class="inline w-4 h-4 ml-1" />
                        </th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users.data" :key="user.id"
                        class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-4 py-3">
                            <input type="checkbox" :value="user.id" v-model="selectedIds" />
                        </td>
                        <td class="px-4 py-3">{{ user.name }}</td>
                        <td class="px-4 py-3">{{ user.email }}</td>
                        <td class="px-4 py-3">
                            <RoleBadge :role="user.role" />
                        </td>
                        <td class="px-4 py-3 text-right space-x-2 flex sm:justify-end flex-wrap gap-2">
                            <Button size="sm" @click="editUser(user)" icon="mdi:pencil">Edit</Button>
                            <Button size="sm" variant="danger" @click="removeUser(user)"
                                :disabled="currentUser.id === user.id" icon="mdi:trash-can-outline">Delete</Button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination :links="users.links" :meta="users.meta" />

        <!-- Modals -->
        <UserForm v-if="showForm" :user="selectedUser" @close="showForm = false" @refresh="fetch" />
        <ConfirmDialog v-if="confirmDelete && !confirmDelete.bulk" :title="`Delete ${confirmDelete.name}`"
            :message="`Are you sure you want to delete ${confirmDelete.name}?`" @cancel="confirmDelete = null"
            @confirm="confirmRemove" />


        <ConfirmDialog v-if="confirmDelete && confirmDelete.bulk" title="Delete Selected Users"
            message="Are you sure you want to delete the selected users? This cannot be undone."
            confirmText="Yes, Delete All" @cancel="confirmDelete = null" @confirm="confirmBulkRemove" />

    </div>
</template>
