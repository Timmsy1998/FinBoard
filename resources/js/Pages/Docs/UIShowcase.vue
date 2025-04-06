<script setup>
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

import ChartWidget from '@/Components/dashboard/ChartWidget.vue'
import SparklineWidget from '@/Components/dashboard/SparklineWidget.vue'

import { ref } from 'vue'

const showModal = ref(false)
const showConfirm = ref(false)
const switchValue = ref(false)
const inputValue = ref('')
const selectedOption = ref('option1')
const textValue = ref('Sample text area content')
defineOptions({ layout: AuthenticatedLayout })
</script>

<template>
    <div class="space-y-12 px-6 py-12 max-w-6xl mx-auto">
        <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">🎨 UI Component Showcase</h1>

        <!-- UI Components Section -->
        <section>
            <h2 class="text-xl font-medium text-gray-700 dark:text-gray-200 mb-4">UI Components</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <Card title="Button">
                    <Button>Primary Button</Button>
                </Card>

                <Card title="Input">
                    <Label for="example-input" text="Name" />
                    <Input id="example-input" v-model="inputValue" placeholder="Your name" />
                </Card>

                <Card title="Select">
                    <Select v-model="selectedOption" :options="[
                        { label: 'Option 1', value: 'option1' },
                        { label: 'Option 2', value: 'option2' },
                    ]" />
                </Card>

                <Card title="Switch">
                    <Switch v-model="switchValue" label="Enable feature" />
                </Card>

                <Card title="TextArea">
                    <TextArea v-model="textValue" placeholder="Enter a description..." />
                </Card>

                <Card title="Modal">
                    <Button @click="showModal = true">Show Modal</Button>
                    <Modal :visible="showModal" title="Sample Modal" @close="showModal = false">
                        <p>This is a custom modal using your component.</p>
                    </Modal>
                </Card>

                <Card title="Confirm Dialog">
                    <Button variant="danger" @click="showConfirm = true">Delete Something</Button>
                    <ConfirmDialog v-if="showConfirm" title="Are you sure?" message="This cannot be undone."
                        @cancel="showConfirm = false" @confirm="showConfirm = false" />
                </Card>

                <Card title="Toast Notification">
                    <Toast message="✔️ Changes saved successfully!" />
                </Card>

                <Card title="Pagination">
                    <Pagination :current-page="1" :total-pages="3" />
                </Card>

                <Card title="Error Boundary">
                    <ErrorBoundary>
                        <template #default>
                            <div>Safe content goes here.</div>
                        </template>
                    </ErrorBoundary>
                </Card>

                <Card title="Form Error">
                    <FormError message="This field is required." />
                </Card>

                <Card title="IconWrapper + NavItem">
                    <IconWrapper icon="mdi:rocket-launch" />
                    <div class="mt-2">
                        <NavItem icon="mdi:home" label="Home" href="/" />
                    </div>
                </Card>
            </div>
        </section>

        <!-- Dashboard Widgets Section -->
        <section>
            <h2 class="text-xl font-medium text-gray-700 dark:text-gray-200 mb-4 mt-10">📊 Dashboard Widgets</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <Card title="Chart Widget">
                    <ChartWidget title="Sample Chart" :labels="['Mon', 'Tue', 'Wed']" :dataset="{
                        label: 'Balance',
                        data: [100, 150, 120],
                        backgroundColor: 'rgba(79, 70, 229, 0.2)',
                        borderColor: '#4F46E5',
                    }" />
                </Card>

                <Card title="Sparkline Widget">
                    <SparklineWidget title="Sparkline: Last 5 Days" :data="[10, 22, 18, 26, 30]" />
                </Card>

                <Card title="Stat Tile">
                    <div class="grid grid-cols-2 gap-4">
                        <StatTile label="Monthly Revenue" :value="12450.75" currency="USD" icon="mdi:cash-multiple" />
                        <StatTile label="New Users" :value="308" icon="mdi:account-plus" />
                    </div>
                </Card>
            </div>
        </section>
    </div>
</template>
