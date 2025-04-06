<script setup>
import { Line } from 'vue-chartjs'
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    CategoryScale,
    LinearScale,
    PointElement,
    Filler
} from 'chart.js'

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    LineElement,
    CategoryScale,
    LinearScale,
    PointElement,
    Filler
)

defineProps({
    title: String,
    labels: Array,
    dataset: Object,
})
</script>

<template>
    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
        <h2 class="text-md font-semibold mb-4 text-gray-800 dark:text-gray-100">{{ title }}</h2>

        <div class="relative" style="height: 260px;">
            <Line :data="{
                labels,
                datasets: [dataset],
            }" :options="{
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
            },
            scales: {
                x: {
                    ticks: { color: '#888' },
                    grid: { color: '#444', drawBorder: false },
                },
                y: {
                    beginAtZero: true,
                    ticks: { color: '#888' },
                    grid: { color: '#444', drawBorder: false },
                },
            },
        }" />
        </div>
    </div>
</template>
