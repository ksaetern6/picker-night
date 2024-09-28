import { ref } from "vue"

export const useLoading = (initializedState: false) => {
    const isLoading = ref<boolean>(initializedState)

    const startLoading = (): void => {
        isLoading.value = true
    }

    const stopLoading = (): void => {
        isLoading.value = false
    }

    const withLoading = (handler: () => Promise<void>) => async () => {
        if (isLoading.value) return

        startLoading()

        await handler().catch((err) => console.log(err))

        stopLoading()
    }

    return {
        isLoading,
        startLoading,
        stopLoading,
        withLoading,
    }
}
