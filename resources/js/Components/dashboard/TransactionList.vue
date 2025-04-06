<script setup>
import { Icon } from '@iconify/vue'
import { ref, computed } from 'vue'
import { formatCurrency } from '@/utils/currency'

const props = defineProps({
    transactions: {
        type: Array,
        default: () => [],
    },
    currency: String,
    rate: Number,
    perPage: {
        type: Number,
        default: 5,
    },
})

// Pagination logic
const currentPage = ref(1)
const totalPages = computed(() => Math.ceil(props.transactions.length / props.perPage))
const paginated = computed(() =>
    props.transactions.slice((currentPage.value - 1) * props.perPage, currentPage.value * props.perPage)
)

// Helpers
const categoryIcons = {
    groceries: 'mdi:cart-outline',
    salary: 'mdi:cash-multiple',
    crypto: 'mdi:currency-btc',
    travel: 'mdi:airplane',
    dining: 'mdi:silverware-fork-knife',
    default: 'mdi:bank-transfer',
}

const categoryColors = {
    groceries: 'bg-yellow-100 text-yellow-800',
    salary: 'bg-green-100 text-green-800',
    crypto: 'bg-indigo-100 text-indigo-800',
    travel: 'bg-blue-100 text-blue-800',
    dining: 'bg-red-100 text-red-800',
    default: 'bg-gray-100 text-gray-800',
}
</script>

<template>
    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-md font-semibold text-gray-800 dark:text-gray-100">Recent Transactions</h2>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Page {{ currentPage }} / {{ totalPages }}
            </div>
        </div>

        <!-- List -->
        <ul class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
            <li v-for="(tx, index) in paginated" :key="index" class="py-3 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <!-- Category Icon -->
                    <Icon :icon="categoryIcons[tx.category] || categoryIcons.default" class="w-5 h-5 text-gray-400" />

                    <div>
                        <div class="font-medium text-gray-700 dark:text-gray-200">{{ tx.label }}</div>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium"
                            :class="categoryColors[tx.category] || categoryColors.default">
                            {{ tx.category }}
                        </span>
                    </div>
                </div>

                <div :class="tx.amount < 0 ? 'text-red-500' : 'text-green-500'">
                    {{ tx.amount < 0 ? `(${formatCurrency(Math.abs(tx.amount * rate), currency)})` :
                        formatCurrency(tx.amount * rate, currency) }} </div>
            </li>
        </ul>

        <!-- Pagination Controls -->
        <div class="flex justify-between mt-4 text-sm">
            <button @click="currentPage--" :disabled="currentPage === 1"
                class="text-blue-600 hover:underline disabled:text-gray-400">
                ← Previous
            </button>

            <button @click="currentPage++" :disabled="currentPage === totalPages"
                class="text-blue-600 hover:underline disabled:text-gray-400">
                Next →
            </button>
        </div>
    </div>
</template>
