import { ref, watch, computed, onMounted } from 'vue'
import { fetchExchangeRates } from '@/utils/fetchExchangeRates'

const baseCurrency = 'USD'

const currency = ref(localStorage.getItem('currency') || baseCurrency)
const exchangeRates = ref({})
const lastUpdated = ref(null)

const loadExchangeRates = async () => {
    const rates = await fetchExchangeRates(baseCurrency)
    if (rates) {
        exchangeRates.value = rates
        lastUpdated.value = new Date()
    }
}

watch(currency, () => {
    localStorage.setItem('currency', currency.value)
})

export function useCurrency() {
    return {
        currency,
        rate: computed(() => exchangeRates.value[currency.value] || 1),
        setCurrency: (val) => (currency.value = val),
        baseCurrency,
        exchangeRates,
        lastUpdated,
        reloadRates: loadExchangeRates,
    }
}
