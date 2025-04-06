<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { ref, onMounted, watch } from 'vue'
import StepIntro from './StepIntro.vue'
import StepOne from './StepOne.vue'
import StepTwo from './StepTwo.vue'
import StepThree from './StepThree.vue'
import { Icon } from '@iconify/vue'
import { router, usePage } from '@inertiajs/vue3'
import confetti from 'canvas-confetti'

const steps = [StepIntro, StepOne, StepTwo, StepThree]
console.log('Steps loaded:', steps.map(s => s?.name || typeof s))

const currentStep = ref(0)
const isSubmitting = ref(false)
const showSuccess = ref(false)

const formData = ref({
    app_name: 'FinBoard',
    base_currency: 'USD',
    api_key_enabled: false,
    api_key: '',
    logo: null,
    user_name: '',
    email: '',
    password: '',
    password_confirmation: '',
    timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
})

const loadLocalData = () => {
    const saved = localStorage.getItem('finboard_setup')
    if (saved) Object.assign(formData.value, JSON.parse(saved))
}
const saveToLocal = () => {
    localStorage.setItem('finboard_setup', JSON.stringify(formData.value))
}

watch(formData, saveToLocal, { deep: true })
watch(currentStep, val => console.log('Step advanced to:', val))
onMounted(loadLocalData)

const nextStep = () => {
    if (currentStep.value < steps.length - 1) currentStep.value++
}
const prevStep = () => {
    if (currentStep.value > 0) currentStep.value--
}

const completeSetup = () => {
    isSubmitting.value = true

    const form = new FormData()
    for (const [key, val] of Object.entries(formData.value)) {
        form.append(key, val ?? '')
    }

    router.post('/setup/complete', form, {
        forceFormData: true,
        onSuccess: () => {
            localStorage.removeItem('finboard_setup')
            showSuccess.value = true

            // Fire confetti
            confetti({
                particleCount: 150,
                spread: 70,
                origin: { y: 0.6 }
            })

            setTimeout(() => {
                router.visit('/login')
            }, 1500)
        },
        onFinish: () => {
            isSubmitting.value = false
        }
    })
}
</script>

<template>
    <GuestLayout>
        <div class="w-full max-w-2xl relative bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden p-6">

            <!-- Progress Bar -->
            <div class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden mb-6">
                <div class="h-full bg-blue-500 transition-all duration-500"
                    :style="{ width: `${((currentStep + 1) / steps.length) * 100}%` }" />
            </div>

            <!-- Setup Steps -->
            <div v-if="!showSuccess">
                <transition name="fade" mode="out-in">
                    <component v-if="steps[currentStep]" :is="steps[currentStep]" :key="`step-${currentStep}`"
                        v-model="formData" @next="nextStep" @back="prevStep" @complete="completeSetup"
                        :step="currentStep" :isLast="currentStep === steps.length - 1" :isFirst="currentStep === 0" />
                    <div v-else class="text-red-600">⚠️ Step component not loaded</div>
                </transition>
            </div>

            <!-- ✅ Success Animation -->
            <transition name="fade">
                <div v-if="showSuccess" class="text-center py-16">
                    <Icon icon="mdi:check-circle" class="text-green-500 w-16 h-16 mx-auto mb-4" />
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Setup Complete</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Redirecting to login...</p>
                </div>
            </transition>

            <!-- Loading Overlay -->
            <div v-if="isSubmitting"
                class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <svg class="animate-spin h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                </svg>
            </div>
        </div>
    </GuestLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
