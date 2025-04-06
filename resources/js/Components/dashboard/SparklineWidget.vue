<script setup>
import { Line } from 'vue-chartjs'
import {
    Chart as ChartJS,
    LineElement, PointElement, LinearScale, CategoryScale, Tooltip, Legend
} from 'chart.js'
import { ref, watch, onMounted } from 'vue'
import WidgetWrapper from '@/Components/ui/WidgetWrapper.vue'

// Register necessary chart.js components
ChartJS.register(LineElement, PointElement, LinearScale, CategoryScale, Tooltip, Legend)

// Props for the component
const props = defineProps({
    title: {
        type: String,
        default: 'Currency Trend',
    },
    datasets: {
        type: Array,
        required: true,
    },
    labels: {
        type: Array,
        default: () => [],
    },
})

// Control loading state for skeleton
const hasLoaded = ref(false)

// Mark as loaded once we have data
onMounted(() => {
    if (props.datasets?.length && props.datasets[0]?.data?.length) {
        hasLoaded.value = true
    }
})

watch(() => props.datasets, (val) => {
    if (val?.length && val[0]?.data?.length) {
        hasLoaded.value = true
    }
})
</script>

<template>
    <WidgetWrapper :loading="!hasLoaded">
        <h3 class="text-sm font-medium mb-2 text-gray-700 dark:text-gray-100">{{ title }}</h3>

        <Line v-if="hasLoaded" :data="{
            labels: props.labels.length ? props.labels : props.datasets[0].data.map((_, i) => `Day ${i + 1}`),
            datasets: props.datasets
        }" :options="{
    responsive: true,
    animation: {
        duration: 1000,
        easing: 'easeOutQuart',
    },
    plugins: {
        legend: {
            display: true,
            labels: {
                color: '#999',
                font: { size: 12 },
            }
        },
        tooltip: {
            enabled: true,
            mode: 'index',
            intersect: false,
            callbacks: {
                label: context => `${context.dataset.label}: ${context.formattedValue}%`
            }
        },
    },
    elements: {
        point: { radius: 0 }
    },
    scales: {
        x: { display: false },
        y: {
            display: false,
            ticks: {
                callback: (value) => `${value}%`
            }
        },
    }
}" height="70" />

    </WidgetWrapper>
</template>
