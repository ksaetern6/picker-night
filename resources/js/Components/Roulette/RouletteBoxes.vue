<script setup lang="ts">
import autoAnimate from '@formkit/auto-animate';
import { computed, onMounted, ref } from 'vue';
import ScrollArea from '../ui/scroll-area/ScrollArea.vue';


interface RouletteBoxesProps {
    options: string[]
}

interface interval {
    duration: number
    speed: number
}

const props = defineProps<RouletteBoxesProps>()
const boxes = ref<HTMLElement>()
const highlightedIndex = ref<number>(0)
const isCycling = ref<boolean>(false);

const cleanOptions = computed((): string[] => {
    return props.options.filter((option) => option.trim().length !== 0)
})

const cycleItems = (): void => {
    let index = 0;
    const duration = 10000
    const elements = cleanOptions.value.length
    const startTime = Date.now()
    const intervalStack: interval[] = [
        { duration: 7000, speed: 100 },
        // { duration: 4000, speed: 10000 },
        // { duration: 4000, speed: 1400 },
        // { duration: 2000, speed: 160 },
        // { duration: 2000, speed: 1000 },
        // { duration: 2000, speed: 1000 },
    ]
    isCycling.value = true

    intervalStack.map(({duration, speed}) => {
        console.log(`${duration} - ${speed}`)

        const interval = setInterval(() => {
            const currentTime = Date.now()

            if (currentTime - startTime <= duration) {
                highlightedIndex.value = index

                const itemEl = document.querySelectorAll('.box')[index]
                if (itemEl) {
                    itemEl.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    })
                }

                index = (index + 1) % elements;
            } else {
                clearInterval(interval)
            }
        }, speed)
    })
}

defineExpose({
    cycleItems
})

onMounted(() => {
    if (boxes.value) {
        autoAnimate(boxes.value)
    }
})
</script>

<template>
    <div class="text-center my-auto flex flex-col items-center">
        <ScrollArea class="h-80">
            <div class="flex flex-wrap" ref="boxes">
                <div v-if="cleanOptions.length === 0">
                    <p>Start adding options to the right hand side</p>
                </div>
                <div v-for="(option, index) in cleanOptions"
                    :key="index"
                    :class="[{'border-yellow-500': index === highlightedIndex && isCycling}]"
                    class="box box-border border-red-500 border m-2 p-4">
                        {{ option }}
                </div>
            </div>
        </ScrollArea>
    </div>
</template>