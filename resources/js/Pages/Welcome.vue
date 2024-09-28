<script setup lang="ts">
import List from '@/Components/Home/List.vue';
import RouletteBoxes from '@/Components/Roulette/RouletteBoxes.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

let options = ref<string[]>([''])
let roulette = ref<InstanceType<typeof RouletteBoxes>>()

const listUpdate = (list: string[]): void => {
    options.value = list
}

const executeRoulette = (execute: boolean): void => {
    if (execute) {
        roulette.value?.cycleItems()
    }
}
</script>

<template>
    <Head title="Home" />
    <div class="bg-gray-50 text-black/50 dark:bg-inherit dark:text-white/50">
        <!-- <h1 class="text-center text-3xl pt-4">Randomizer (TBD)</h1> -->

        <div class="py-8 px-16 h-screen">
            <div class="grid grid-cols-4 gap-2 h-full">
                <RouletteBoxes 
                    :options="options" 
                    class="col-span-3"
                    ref="roulette"/>
                <List 
                    class="col-span-1"
                    @list-update="listUpdate"
                    @execute-roulette="executeRoulette">
                </List>
            </div>
        </div>
    </div>
</template>
 