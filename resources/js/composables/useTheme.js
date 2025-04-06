import { ref, watchEffect } from 'vue'

const theme = ref(localStorage.getItem('theme') || 'light')

watchEffect(() => {
    const html = document.documentElement
    if (theme.value === 'dark') {
        html.classList.add('dark')
    } else {
        html.classList.remove('dark')
    }
    localStorage.setItem('theme', theme.value)
})

export function useTheme() {
    return {
        theme,
        toggleTheme: () => {
            theme.value = theme.value === 'dark' ? 'light' : 'dark'
        },
    }
}
