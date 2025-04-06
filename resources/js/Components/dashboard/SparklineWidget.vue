<script setup>
import { Line } from 'vue-chartjs'
import {
    Chart as ChartJS,
    Title, Tooltip, Legend,
    LineElement, PointElement, LinearScale, CategoryScale
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, LinearScale, CategoryScale)

const props = defineProps({
    label: String,
    currency: String,
    values: Array,
    color: {
        type: String,
        default: 'rgba(59, 130, 246, 1)' // Tailwind blue-500
    },
})
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
        <h3 class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-100">{{ label }}</h3>
        <div class="text-xl font-semibold text-gray-800 dark:text-white mb-2">
            {{ currency }} {{ values[values.length - 1].toFixed(2) }}
        </div>
        <Line :data="{
            labels: values.map((_, i) => i + 1),
            datasets: [{
                data: values,
                borderColor: color,
                backgroundColor: 'transparent',
                tension: 0.3
            }]
        }" :options="{
        responsive: true,
        plugins: { legend: { display: false }, tooltip: { enabled: false } },
        elements: { point: { radius: 0 } },
        scales: { x: { display: false }, y: { display: false } }
    }" height="50" />
    </div>
</template>
