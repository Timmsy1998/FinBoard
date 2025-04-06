<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    rates: {
        type: Array,
        required: true
    }
})

const showAll = ref(false)

const priority = ['EUR', 'GBP', 'JPY', 'INR', 'AUD', 'CAD']

const sortedRates = computed(() => {
    const ratesMap = new Map(props.rates.map(rate => [rate.target_currency, rate]))

    // ✅ Prioritized rates (only if they exist in actual rates)
    const prioritized = priority
        .map(code => ratesMap.get(code))
        .filter(rate => !!rate)

    const seen = new Set(prioritized.map(rate => rate.target_currency))

    // ✅ All remaining rates (excluding already shown priority ones)
    const others = props.rates.filter(rate => !seen.has(rate.target_currency))

    const combined = [...prioritized, ...others]

    return showAll.value ? combined : combined.slice(0, 6)
})
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
        <h2 class="text-md font-semibold mb-4 text-gray-800 dark:text-white">Live Exchange Rates</h2>

        <ul class="space-y-2 text-sm">
            <li v-for="rate in sortedRates" :key="rate.target_currency"
                class="flex justify-between text-gray-700 dark:text-gray-200">
                <span>USD → {{ rate.target_currency }}</span>
                <span class="font-mono">{{ Number(rate.rate).toFixed(4) }}</span>
            </li>
        </ul>

        <div v-if="sortedRates.length > 0" class="mt-4 text-xs text-gray-400 dark:text-gray-500">
            Updated: {{ new Date(sortedRates[0].fetched_at).toLocaleString() }}
        </div>

        <button v-if="props.rates.length > 6" @click="showAll = !showAll"
            class="text-blue-600 text-xs mt-2 hover:underline">
            {{ showAll ? 'Show Less' : 'Show All' }}
        </button>
    </div>
</template>
