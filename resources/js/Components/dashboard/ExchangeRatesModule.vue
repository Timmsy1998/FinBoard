<script setup>
import { ref, onMounted, computed } from 'vue'
import WidgetWrapper from '@/Components/ui/WidgetWrapper.vue'

const rates = ref([])
const loading = ref(true)
const showAll = ref(false)
const baseCurrency = ref('USD') // fallback

const priority = ['EUR', 'GBP', 'JPY', 'INR', 'AUD', 'CAD']

onMounted(async () => {
    try {
        const res = await fetch('/api/exchange-rates')
        const data = await res.json()

        if (data?.rates) {
            rates.value = data.rates
            baseCurrency.value = data.base_currency || 'USD'
        } else {
            console.warn('No rates returned')
        }
    } catch (e) {
        console.error('Failed to load exchange rates:', e)
    } finally {
        loading.value = false
    }
})

const sortedRates = computed(() => {
    const ratesMap = new Map(rates.value.map(rate => [rate.target_currency, rate]))
    const prioritized = priority.map(code => ratesMap.get(code)).filter(Boolean)
    const seen = new Set(prioritized.map(r => r.target_currency))
    const others = rates.value.filter(r => !seen.has(r.target_currency))
    return showAll.value ? [...prioritized, ...others] : [...prioritized, ...others].slice(0, 6)
})

const lastUpdated = computed(() => {
    return sortedRates.value.length > 0
        ? new Date(sortedRates.value[0].fetched_at).toLocaleString()
        : null
})
</script>

<template>
    <WidgetWrapper :loading="loading">
        <h2 class="text-md font-semibold mb-4 text-gray-800 dark:text-white">
            Live Exchange Rates
        </h2>

        <ul class="space-y-2 text-sm">
            <li v-for="rate in sortedRates" :key="rate.target_currency"
                class="flex justify-between text-gray-700 dark:text-gray-200">
                <span>{{ baseCurrency }} → {{ rate.target_currency }}</span>
                <span class="font-mono">{{ Number(rate.rate).toFixed(4) }}</span>
            </li>
        </ul>

        <div v-if="lastUpdated" class="mt-4 text-xs text-gray-400 dark:text-gray-500">
            Updated: {{ lastUpdated }}
        </div>

        <button v-if="rates.length > 6" @click="showAll = !showAll" class="text-blue-600 text-xs mt-2 hover:underline">
            {{ showAll ? 'Show Less' : 'Show All' }}
        </button>
    </WidgetWrapper>
</template>
