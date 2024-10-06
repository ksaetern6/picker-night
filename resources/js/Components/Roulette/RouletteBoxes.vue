<script setup lang="ts">
import autoAnimate from '@formkit/auto-animate';
import { computed, onMounted, ref } from 'vue';
import ScrollArea from '../ui/scroll-area/ScrollArea.vue';
import ConfirmationDialog from './ConfirmationDialog.vue';

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
const isCycling = ref<boolean>(false)
const openDialog = ref<boolean>(false)
const chosenItem = ref<string>('')

const cleanOptions = computed((): string[] => {
    return props.options.filter((option) => option.trim().length !== 0)
})

const cycleItems = () => {
    // let x = 6
    let speed = 50
    let minSpeed = 220
    let index = 0
    let threshold = speed
    // const interval = 2
    const highSpeedThreshold = 100
    const mediumThreshold = 150
    const slowThreshold = 200
    const elements = cleanOptions.value.length

    isCycling.value = true

    const selectIndex = () => {
        highlightedIndex.value = index

        const itemEl = document.querySelectorAll('.box')[index]

        if (itemEl) {
            itemEl.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            })
        }

        index = (index + 1) % elements;

        // if (speed % x === 0) {
        //     speed += interval
        //     x = 6
        // } else {
        //     x -= 2
        // }

        
        if (threshold == highSpeedThreshold) {
            speed = 102
        }
        else if (threshold == mediumThreshold) {
            speed = 152
        } 
        else if (threshold == slowThreshold) {
            speed = 202
        } 
        else if (threshold > slowThreshold) {
            speed += 2
        }
        
        threshold += 2

        setTimeout(() => {
            if (speed >= minSpeed) {
                chosenItem.value = props.options[index - 1]
                setTimeout(() => {
                    openDialog.value = true
                }, 1000)
            } else {
                selectIndex(); // Recursively call the function to continue looping
            }
        }, speed);
    }

    selectIndex()
}
defineExpose({ cycleItems })

onMounted(() => {
    if (boxes.value) {
        autoAnimate(boxes.value)
    }
})
</script>

<template>
    <div class="text-center my-auto flex flex-col items-center h-full">
        <ConfirmationDialog 
            :open="openDialog"
            :title="chosenItem"
            @toggle-dialog="(e) => openDialog = e" />
        <ScrollArea class="max-h-[80vh]">
            <div class="flex flex-wrap items-start justify-start" ref="boxes">
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