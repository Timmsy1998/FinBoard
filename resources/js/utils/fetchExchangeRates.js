export async function fetchExchangeRates(base = 'USD', apiKey = '') {
    try {
        const url = new URL('https://api.exchangerate.host/live')
        url.searchParams.set('source', base)
        if (apiKey) {
            url.searchParams.set('access_key', apiKey)
        }

        const res = await fetch(url.toString())
        const data = await res.json()

        if (!data?.success || !data.quotes) {
            throw new Error('Invalid API response')
        }

        // Transform the quotes object to a uniform array
        const rates = Object.entries(data.quotes).map(([pair, rate]) => ({
            base_currency: base,
            target_currency: pair.slice(3), // Remove base prefix, e.g., USDGBP → GBP
            rate,
            fetched_at: new Date().toISOString(),
        }))

        return rates
    } catch (error) {
        console.error('[ExchangeRate] Failed to fetch:', error)
        return []
    }
}
