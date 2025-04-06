<template>
    <WidgetWrapper :loading="!hasLoaded">
        <h3 class="text-sm font-medium mb-2 text-gray-700 dark:text-gray-100">{{ title }}</h3>
        <Line :data="{
            labels: props.data.map((_, i) => i + 1),
            datasets: [{
                data: props.data,
                borderColor: color,
                borderWidth: 2,
                backgroundColor: 'transparent',
                tension: 0.4,
            }]
        }" :options="{
            responsive: true,
            animation: {
                duration: 1000,
                easing: 'easeOutQuart',
            },
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false },
            },
            elements: { point: { radius: 0 } },
            scales: { x: { display: false }, y: { display: false } }
        }" height="70" />
    </WidgetWrapper>
</template>

<script setup>
import { Line } from 'vue-chartjs'
import {
    Chart as ChartJS,
    LineElement, PointElement, LinearScale, CategoryScale, Tooltip, Legend
} from 'chart.js'
import { ref, watch, onMounted } from 'vue'
import WidgetWrapper from '@/Components/ui/WidgetWrapper.vue'

ChartJS.register(LineElement, PointElement, LinearScale, CategoryScale, Tooltip, Legend)

const props = defineProps({
    title: String,
    data: Array,
    color: {
        type: String,
        default: '#3B82F6',
    },
})

const hasLoaded = ref(false)

onMounted(() => {
    if (props.data?.length) hasLoaded.value = true
})

watch(() => props.data, (val) => {
    if (val?.length) hasLoaded.value = true
})
</script>