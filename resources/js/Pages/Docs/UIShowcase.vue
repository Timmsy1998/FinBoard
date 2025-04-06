<script setup>
import { ref, onMounted } from 'vue'
import Button from '@/Components/ui/Button.vue'
import Card from '@/Components/ui/Card.vue'
import ConfirmDialog from '@/Components/ui/ConfirmDialog.vue'
import ErrorBoundary from '@/Components/ui/ErrorBoundary.vue'
import FormError from '@/Components/ui/FormError.vue'
import IconWrapper from '@/Components/ui/IconWrapper.vue'
import Input from '@/Components/ui/Input.vue'
import Label from '@/Components/ui/Label.vue'
import Modal from '@/Components/ui/Modal.vue'
import NavItem from '@/Components/ui/NavItem.vue'
import Pagination from '@/Components/ui/Pagination.vue'
import Select from '@/Components/ui/Select.vue'
import StatTile from '@/Components/ui/StatTile.vue'
import Switch from '@/Components/ui/Switch.vue'
import TextArea from '@/Components/ui/TextArea.vue'
import Toast from '@/Components/ui/Toast.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

// Dashboard widgets
import ChartWidget from '@/Components/dashboard/ChartWidget.vue'
import SparklineWidget from '@/Components/dashboard/SparklineWidget.vue'
import WidgetWrapper from '@/Components/ui/WidgetWrapper.vue'

const showModal = ref(false)
const showConfirm = ref(false)
const switchValue = ref(false)
const inputValue = ref('')
const selectedOption = ref('option1')
const textValue = ref('Sample text area content')

// Simulate loading state for widgets
const widgetLoading = ref(true)

onMounted(() => {
    setTimeout(() => {
        widgetLoading.value = false
    }, 1500)
})

defineOptions({ layout: AuthenticatedLayout })
</script>

<template>
    <div class="space-y-12 px-6 py-12 max-w-6xl mx-auto">
        <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">🎨 UI Component Showcase</h1>

        <!-- UI Components -->
        <section>
            <h2 class="text-xl font-medium text-gray-700 dark:text-gray-200 mb-4">UI Components</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <Card title="Button">
                    <Button>Primary Button</Button>
                </Card>

                <Card title="Input">
                    <Label for="input-demo">Your Name</Label>
                    <Input id="input-demo" v-model="inputValue" placeholder="e.g. Jane Doe" />
                </Card>

                <Card title="Select">
                    <Select v-model="selectedOption" :options="[
                        { label: 'Option 1', value: 'option1' },
                        { label: 'Option 2', value: 'option2' },
                    ]" />
                </Card>

                <Card title="Switch">
                    <Switch v-model="switchValue" label="Enable Feature" />
                </Card>

                <Card title="TextArea">
                    <TextArea v-model="textValue" placeholder="Tell us more..." />
                </Card>

                <Card title="Modal">
                    <Button @click="showModal = true">Open Modal</Button>
                    <Modal :visible="showModal" title="Hello Modal" @close="showModal = false">
                        <p>This is a custom modal.</p>
                    </Modal>
                </Card>

                <Card title="Confirm Dialog">
                    <Button variant="danger" @click="showConfirm = true">Danger Zone</Button>
                    <ConfirmDialog v-if="showConfirm" title="Confirm Action" message="This can't be undone."
                        @cancel="showConfirm = false" @confirm="showConfirm = false" />
                </Card>

                <Card title="Toast Notification">
                    <Toast message="✅ Saved successfully!" />
                </Card>

                <Card title="Pagination">
                    <Pagination :links="[
                        { url: null, label: '&laquo;', active: false },
                        { url: '#', label: '1', active: true },
                        { url: '#', label: '2', active: false },
                        { url: '#', label: '&raquo;', active: false }
                    ]" :meta="{ from: 1, to: 10, total: 30 }" />
                </Card>

                <Card title="Error Boundary">
                    <ErrorBoundary>
                        <template #default>
                            <div>Everything is fine.</div>
                        </template>
                    </ErrorBoundary>
                </Card>

                <Card title="Form Error">
                    <FormError message="This field is required." />
                </Card>

                <Card title="IconWrapper + NavItem">
                    <IconWrapper icon="mdi:star" />
                    <div class="mt-2">
                        <NavItem icon="mdi:home" label="Home" href="/" />
                    </div>
                </Card>
            </div>
        </section>

        <!-- Dashboard Widgets -->
        <section>
            <h2 class="text-xl font-medium text-gray-700 dark:text-gray-200 mb-4 mt-10">📊 Dashboard Widgets</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <WidgetWrapper :loading="widgetLoading">
                    <ChartWidget title="Balance Chart" :labels="['Mon', 'Tue', 'Wed']" :dataset="{
                        label: 'Balance',
                        data: [100, 150, 120],
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderColor: '#3B82F6',
                    }" />
                </WidgetWrapper>

                <WidgetWrapper :loading="widgetLoading">
                    <SparklineWidget title="Weekly Sparkline" :data="[50, 60, 80, 70, 90]" />
                </WidgetWrapper>

                <WidgetWrapper :loading="widgetLoading">
                    <div class="grid grid-cols-2 gap-4">
                        <StatTile label="Monthly Revenue" value="$12,450" icon="mdi:cash-multiple" />
                        <StatTile label="New Users" value="308" icon="mdi:account-plus" />
                    </div>
                </WidgetWrapper>
            </div>
        </section>
    </div>
</template>
