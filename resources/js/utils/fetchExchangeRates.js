export async function fetchExchangeRates(base = 'USD') {
    try {
        const res = await fetch(`https://api.exchangerate.host/latest?base=${base}`)
        const data = await res.json()

        if (!data || !data.rates) throw new Error('Invalid response')

        return data.rates
    } catch (error) {
        console.error('Failed to fetch exchange rates:', error)
        return null
    }
}
