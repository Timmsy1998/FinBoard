export const currencySymbols = {
    USD: '$',
    EUR: '€',
    GBP: '£',
    INR: '₹',
    JPY: '¥',
    AUD: 'A$',
    CAD: 'C$',
}

export function formatCurrency(amount, currency = 'GBP') {
    return new Intl.NumberFormat(undefined, {
        style: 'currency',
        currency,
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(amount)
}

export function convertAndFormat(amount, rate = 1, currency = 'GBP') {
    const converted = amount * rate
    return formatCurrency(converted, currency)
}
