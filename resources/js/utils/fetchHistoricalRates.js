export async function fetchHistoricalRates({ base = 'USD', target = 'EUR', days = 5, apiKey = '' }) {
    const today = new Date()
    const start = new Date(today)
    start.setDate(today.getDate() - (days - 1))

    const startStr = start.toISOString().split('T')[0]
    const endStr = today.toISOString().split('T')[0]

    const url = `https://api.exchangerate.host/timeseries?base=${base}&symbols=${target}&start_date=${startStr}&end_date=${endStr}` +
        (apiKey ? `&access_key=${apiKey}` : '')

    const response = await fetch(url)
    const data = await response.json()

    if (!data.success || !data.rates) throw new Error('Failed to fetch historical rates')

    const sortedDates = Object.keys(data.rates).sort()
    return sortedDates.map(date => data.rates[date][target])
}