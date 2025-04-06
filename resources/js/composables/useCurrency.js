import { ref, watch, computed, onMounted, onUnmounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { fetchExchangeRates } from '@/utils/fetchExchangeRates'

const CACHE_DURATION = 30 * 60 * 1000 // 30 minutes

const currency = ref(localStorage.getItem('currency') || null)
const exchangeRates = ref({})
const lastUpdated = ref(null)
let intervalId = null

export function useCurrency() {
    const page = usePage()

    const baseCurrency = page.props.app.base_currency || 'USD'
    const apiKey = page.props.app.exchange_api_key || ''
    const exchangeRatesEnabled = page.props.app.exchangeRatesEnabled === true

    if (!currency.value) currency.value = baseCurrency

    const rate = computed(() =>
        exchangeRatesEnabled
            ? exchangeRates.value[currency.value] || 1
            : 1
    )

    const loadExchangeRates = async () => {
        if (!exchangeRatesEnabled) return

        const cached = JSON.parse(localStorage.getItem('exchangeRates') || '{}')
        const now = Date.now()

        if (cached?.timestamp && now - cached.timestamp < CACHE_DURATION) {
            exchangeRates.value = cached.rates
            lastUpdated.value = new Date(cached.timestamp)
            return
        }

        const rates = await fetchExchangeRates(baseCurrency, apiKey)
        if (rates) {
            exchangeRates.value = rates
            lastUpdated.value = new Date()
            localStorage.setItem(
                'exchangeRates',
                JSON.stringify({ rates, timestamp: Date.now() })
            )
        }
    }

    watch(currency, () => {
        localStorage.setItem('currency', currency.value)
    })

    onMounted(() => {
        if (exchangeRatesEnabled) {
            loadExchangeRates()
            intervalId = setInterval(loadExchangeRates, CACHE_DURATION)
        }
    })

    onUnmounted(() => {
        if (intervalId) clearInterval(intervalId)
    })

    return {
        currency,
        setCurrency: (val) => (currency.value = val),
        rate,
        baseCurrency,
        exchangeRates,
        exchangeRatesEnabled,
        lastUpdated,
        reloadRates: loadExchangeRates,
    }
}
