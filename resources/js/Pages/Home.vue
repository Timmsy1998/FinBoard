<script setup>
// Layout & Core
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { usePage } from '@inertiajs/vue3'

// Components
import StatTile from '@/Components/ui/StatTile.vue'
import ChartWidget from '@/Components/dashboard/ChartWidget.vue'
import SparklineWidget from '@/Components/dashboard/SparklineWidget.vue'
import TransactionList from '@/Components/dashboard/TransactionList.vue'
import ExchangeRatesModule from '@/Components/dashboard/ExchangeRatesModule.vue'

// Composables & Utilities
import { useCurrency } from '@/composables/useCurrency'
import { useDemoHelper } from '@/composables/useDemoHelper'
import { convertAndFormat } from '@/utils/currency'
import { computed, ref, onMounted } from 'vue'

// Inertia props
defineOptions({ layout: AuthenticatedLayout })
defineProps({ exchangeRates: Array })

const page = usePage()
const app = page.props.app
const settings = page.props.settings
const user = computed(() => page.props.auth?.user ?? {})

const { isDemo, demoData } = useDemoHelper()
const { currency, rate } = useCurrency()

// Exchange toggles
const exchangeEnabled = computed(() => settings.exchangeEnabled)
const baseCurrency = computed(() => settings.baseCurrency || 'USD')
const shouldShowExchange = computed(() => isDemo.value || app.exchangeRatesEnabled)

// Load data from demo or defaults
const rawBalance = computed(() => isDemo.value ? demoData.value?.balance ?? 0 : 12800)
const rawMonthlyGains = computed(() => isDemo.value ? demoData.value?.monthlyGains ?? 0 : 1210.4)
const rawExpenses = computed(() => isDemo.value ? demoData.value?.expenses ?? 0 : 3500)

// Format display values
const totalBalance = computed(() => exchangeEnabled.value
    ? convertAndFormat(rawBalance.value, rate.value, currency.value)
    : `${rawBalance.value} ${baseCurrency.value}`)

const monthlyGains = computed(() => exchangeEnabled.value
    ? convertAndFormat(rawMonthlyGains.value, rate.value, currency.value)
    : `${rawMonthlyGains.value} ${baseCurrency.value}`)

const expenses = computed(() => exchangeEnabled.value
    ? convertAndFormat(rawExpenses.value, rate.value, currency.value)
    : `${rawExpenses.value} ${baseCurrency.value}`)

// Transaction list
const transactions = ref([])

// Currency trends
const currencyTrends = ref(null)
const loadingTrends = ref(true)

// Color map
const currencyColors = {
    USD: '#3B82F6', EUR: '#10B981', GBP: '#8B5CF6', INR: '#F59E0B',
    AUD: '#EC4899', JPY: '#F43F5E', CAD: '#EAB308', CNY: '#EF4444',
    NZD: '#0EA5E9', ZAR: '#22D3EE',
}

// Normalize for sparkline
function normalizeTrendData(data) {
    const base = data[0] || 1
    return data.map(value => parseFloat((((value - base) / base) * 100).toFixed(2)))
}

const currencyTrendDatasets = computed(() => {
    if (!currencyTrends.value) return []
    return Object.entries(currencyTrends.value).map(([currency, data]) => ({
        label: currency,
        data: normalizeTrendData(data),
        borderColor: currencyColors[currency] || '#9CA3AF',
        backgroundColor: 'transparent',
        tension: 0.4,
    }))
})

// Init
onMounted(async () => {
    if (isDemo.value) {
        // Pull demo data from helper
        currencyTrends.value = demoData.value?.trends ?? {}
        transactions.value = demoData.value?.transactions ?? []
        loadingTrends.value = false
        return
    }

    // Real API fallback
    try {
        const res = await fetch('/api/exchange-trends')
        const json = await res.json()
        currencyTrends.value = json.trends
    } catch (e) {
        console.error('Failed to fetch trends:', e)
    } finally {
        loadingTrends.value = false
    }
})

const chartLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']

const chartData = {
    label: 'Balance',
    data: [12200, 12400, 12300, 12650, 12700, 12550, 12800],
    borderColor: '#4F46E5',
    backgroundColor: 'rgba(99, 102, 241, 0.2)',
    tension: 0.4,
}

</script>


<template>
    <div class="px-6 pt-12 pb-24 space-y-12">
        <!-- Welcome -->
        <div class="flex items-center justify-between">
            <div>
                <h1 v-if="user" class="text-4xl font-light tracking-tight text-gray-900 dark:text-white">
                    Welcome back, <span class="font-semibold">{{ user.name }}</span>
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Here’s your financial overview.</p>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <StatTile label="Total Balance" :value="totalBalance" icon="mdi:wallet-outline"
                icon-color="text-blue-500" />
            <StatTile label="Monthly Gains" :value="monthlyGains" icon="mdi:trending-up" icon-color="text-green-500" />
            <StatTile label="Expenses" :value="expenses" icon="mdi:credit-card-outline" icon-color="text-red-500" />
        </div>

        <!-- Chart + Transactions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <ChartWidget title="Balance Trend (7 Days)" :labels="chartLabels" :dataset="chartData" />
            <TransactionList v-if="transactions.length" :transactions="transactions" :currency="currency.value"
                :rate="rate.value" :perPage="4" />
            <p v-else class="text-gray-500 text-sm mt-2">No transactions found.</p>


            <!-- Exchange Rates -->
            <ExchangeRatesModule v-if="shouldShowExchange" :rates="exchangeRates" />

            <!-- Sparkline -->
            <SparklineWidget v-if="shouldShowExchange" title="5-Day Multi-Currency Trend (% Change)"
                :labels="['5d ago', '4d', '3d', '2d', 'Today']" :datasets="currencyTrendDatasets" />
        </div>
    </div>
</template>
