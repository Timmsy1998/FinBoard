export async function fetchExchangeRates(base = 'USD', apiKey = '') {
    try {
        let url = `https://api.exchangerate.host/latest?base=${base}`
        if (apiKey) {
            url += `&access_key=${apiKey}`
        }

        const res = await fetch(url)
        const data = await res.json()

        if (!data || !data.rates) throw new Error('Invalid response')

        return data.rates
    } catch (error) {
        console.error('Failed to fetch exchange rates:', error)
        return null
    }
}
