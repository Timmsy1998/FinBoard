import { computed, ref, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'

export function useDemoHelper() {
    const page = usePage()

    const isDemo = computed(() =>
        page.props.app?.demoMode === true || page.props.app?.demoMode === '1'
    )

    const demoData = ref({})

    const loadDemoData = async () => {
        try {
            const res = await fetch('/demo/demo-data.json')
            const json = await res.json()
            demoData.value = json
        } catch (e) {
            console.error('Failed to load demo data:', e)
        }
    }

    // Load once if in demo
    if (isDemo.value) {
        loadDemoData()
    }

    const disableInDemo = (fallback = null) => {
        return isDemo.value ? fallback : true
    }

    return {
        isDemo,
        disableInDemo,
        demoData
    }
}
