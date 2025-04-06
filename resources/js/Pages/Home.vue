<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { usePage } from '@inertiajs/vue3'
import StatTile from '@/Components/ui/StatTile.vue'
import ChartWidget from '@/Components/dashboard/ChartWidget.vue'
import TransactionList from '@/Components/dashboard/TransactionList.vue'
import { useCurrency } from '@/composables/useCurrency'
import { convertAndFormat } from '@/utils/currency'
import { computed } from 'vue'
import ExchangeRatesModule from '@/Components/dashboard/ExchangeRatesModule.vue'

defineProps({ exchangeRates: Array })
defineOptions({ layout: AuthenticatedLayout })

const page = usePage()
const user = computed(() => page.props.auth?.user ?? {})
const settings = page.props.settings
const { currency, rate } = useCurrency()

const exchangeEnabled = computed(() => settings.exchangeEnabled)
const baseCurrency = computed(() => settings.baseCurrency || 'USD')

// Fake raw balance data
const rawBalance = 12800
const rawMonthlyGains = 1210.4
const rawExpenses = 3500

// Stats formatting
const totalBalance = computed(() =>
    exchangeEnabled.value ? convertAndFormat(rawBalance, rate.value, currency.value) : `${rawBalance} ${baseCurrency.value}`
)

const monthlyGains = computed(() =>
    exchangeEnabled.value ? convertAndFormat(rawMonthlyGains, rate.value, currency.value) : `${rawMonthlyGains} ${baseCurrency.value}`
)

const expenses = computed(() =>
    exchangeEnabled.value ? convertAndFormat(rawExpenses, rate.value, currency.value) : `${rawExpenses} ${baseCurrency.value}`
)

const chartLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
const chartData = {
    label: 'Balance',
    data: [12200, 12400, 12300, 12650, 12700, 12550, 12800],
    borderColor: '#4F46E5',
    backgroundColor: 'rgba(99, 102, 241, 0.2)',
    tension: 0.4,
}

const transactions = [
    { label: 'Starbucks Coffee', amount: -5.75, category: 'dining' },
    { label: 'January Salary', amount: 3000, category: 'salary' },
    { label: 'Crypto Buy', amount: -420, category: 'crypto' },
    { label: 'Whole Foods', amount: -52.13, category: 'groceries' },
    { label: 'Crypto Sell', amount: 1200, category: 'crypto' },
    { label: 'Flight to NYC', amount: -278, category: 'travel' },
]
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
            <TransactionList :transactions="transactions" :currency="currency.value" :rate="rate.value" :perPage="4" />

            <!-- Conditionally show if exchange enabled -->
            <ExchangeRatesModule v-if="$page.props.app.exchangeRatesEnabled" :rates="exchangeRates" />

        </div>
    </div>
</template>
