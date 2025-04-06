<script setup>
// Layout & core
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { usePage } from '@inertiajs/vue3'

// Components
import StatTile from '@/Components/ui/StatTile.vue'
import ChartWidget from '@/Components/dashboard/ChartWidget.vue'
import SparklineWidget from '@/Components/dashboard/SparklineWidget.vue'
import TransactionList from '@/Components/dashboard/TransactionList.vue'
import ExchangeRatesModule from '@/Components/dashboard/ExchangeRatesModule.vue'

// Utilities
import { useCurrency } from '@/composables/useCurrency'
import { convertAndFormat } from '@/utils/currency'
import { computed, ref, onMounted } from 'vue'

// Set Inertia layout
defineOptions({ layout: AuthenticatedLayout })

// Props
defineProps({ exchangeRates: Array })

// Access user and settings from Inertia page
const page = usePage()
const user = computed(() => page.props.auth?.user ?? {})
const settings = page.props.settings

// Currency handling
const { currency, rate } = useCurrency()
const exchangeEnabled = computed(() => settings.exchangeEnabled)
const baseCurrency = computed(() => settings.baseCurrency || 'USD')

// Sample data (replace with backend fetch eventually)
const rawBalance = 12800
const rawMonthlyGains = 1210.4
const rawExpenses = 3500

// Format currency values conditionally
const totalBalance = computed(() =>
    exchangeEnabled.value
        ? convertAndFormat(rawBalance, rate.value, currency.value)
        : `${rawBalance} ${baseCurrency.value}`
)

const monthlyGains = computed(() =>
    exchangeEnabled.value
        ? convertAndFormat(rawMonthlyGains, rate.value, currency.value)
        : `${rawMonthlyGains} ${baseCurrency.value}`
)

const expenses = computed(() =>
    exchangeEnabled.value
        ? convertAndFormat(rawExpenses, rate.value, currency.value)
        : `${rawExpenses} ${baseCurrency.value}`
)

// Sample chart config for balance trend
const chartLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
const chartData = {
    label: 'Balance',
    data: [12200, 12400, 12300, 12650, 12700, 12550, 12800],
    borderColor: '#4F46E5',
    backgroundColor: 'rgba(99, 102, 241, 0.2)',
    tension: 0.4,
}

// Sample transaction log
const transactions = [
    { label: 'Starbucks Coffee', amount: -5.75, category: 'dining' },
    { label: 'January Salary', amount: 3000, category: 'salary' },
    { label: 'Crypto Buy', amount: -420, category: 'crypto' },
    { label: 'Whole Foods', amount: -52.13, category: 'groceries' },
    { label: 'Crypto Sell', amount: 1200, category: 'crypto' },
    { label: 'Flight to NYC', amount: -278, category: 'travel' },
]

// --- Trends ---
const currencyTrends = ref(null)
const loadingTrends = ref(true)

// Define color palette per currency (expandable)
const currencyColors = {
    USD: '#3B82F6',
    EUR: '#10B981',
    GBP: '#8B5CF6',
    INR: '#F59E0B',
    AUD: '#EC4899',
    JPY: '#F43F5E',
    CAD: '#EAB308',
    CNY: '#EF4444',
    NZD: '#0EA5E9',
    ZAR: '#22D3EE',
}

// --- Helper: Normalize values to % change from Day 1
function normalizeTrendData(data) {
    const base = data[0] || 1
    return data.map(value => parseFloat((((value - base) / base) * 100).toFixed(2)))
}

// --- Computed: Chart-ready normalized datasets
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

onMounted(async () => {
    try {
        const res = await fetch('/api/exchange-trends')
        const json = await res.json()
        if (json?.trends) currencyTrends.value = json.trends
    } catch (e) {
        console.error('Failed to fetch trends:', e)
    } finally {
        loadingTrends.value = false
    }
})
</script>

<template>
    <div class="px-6 pt-12 pb-24 space-y-12">

        <!-- Welcome Message -->
        <div class="flex items-center justify-between">
            <div>
                <h1 v-if="user" class="text-4xl font-light tracking-tight text-gray-900 dark:text-white">
                    Welcome back, <span class="font-semibold">{{ user.name }}</span>
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Here’s your financial overview.
                </p>
            </div>
        </div>

        <!-- Stat Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <StatTile label="Total Balance" :value="totalBalance" icon="mdi:wallet-outline"
                icon-color="text-blue-500" />
            <StatTile label="Monthly Gains" :value="monthlyGains" icon="mdi:trending-up" icon-color="text-green-500" />
            <StatTile label="Expenses" :value="expenses" icon="mdi:credit-card-outline" icon-color="text-red-500" />
        </div>

        <!-- Chart + Transactions + Exchange Data -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <!-- 7-Day Balance Chart -->
            <ChartWidget title="Balance Trend (7 Days)" :labels="chartLabels" :dataset="chartData" />

            <!-- Transaction List -->
            <TransactionList :transactions="transactions" :currency="currency.value" :rate="rate.value" :perPage="4" />

            <!-- Live Exchange Rates (if enabled) -->
            <ExchangeRatesModule v-if="$page.props.app.exchangeRatesEnabled" :rates="exchangeRates" />

            <!-- Combined Sparkline for all currencies -->
            <SparklineWidget v-if="currencyTrendDatasets.length" title="5-Day Multi-Currency Trend (% Change)"
                :labels="['5d ago', '4d', '3d', '2d', 'Today']" :datasets="currencyTrendDatasets" />


        </div>
    </div>
</template>
