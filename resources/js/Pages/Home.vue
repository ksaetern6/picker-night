<script setup lang="ts">
import type { Filter } from '@/Components/Filters/FilterSheet.vue';
import List from '@/Components/Home/List.vue';
import RouletteBoxes from '@/Components/Roulette/RouletteBoxes.vue';
import Toaster from '@/Components/ui/toast/Toaster.vue';
import { usePickerStore } from '@/store/store';
import { Head, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';

interface HomeProps {
    options: string[]
    optionsId: string
    filters: Filter[]
}

const page = usePage()
const store = usePickerStore()

let props = defineProps<HomeProps>();

let listOptions = ref<string[]>([''])
let roulette = ref<InstanceType<typeof RouletteBoxes>>()
let listId = ref<string>(props.optionsId)
// const activeFilters = store.enabledFilters

const allOptions = computed((): string[] => {
    const options: string[] = listOptions.value.length != 0
        ? listOptions.value.filter((f) => f.trim() != '')
        : ['']


    if (store.enabledFilters.length) {
        return options.filter((option) => !store.enabledFiltersOptions.includes(option))
    }

    return options
})

const listUpdate = async (list: string[]): Promise<void> => {
    listOptions.value = list

    const _token: string = (page.props.csrf_token as string)
    const method = listId.value?.length === 0 ? 'POST' : 'PUT'

    fetch('/', {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': _token
        },
        body: JSON.stringify({
            options: listOptions.value.filter((f) => f.trim() != ''),
            options_id: listId.value
        })
    })
    .then(resp => resp.json())
    .then(data => listId.value = data.id)
}

const executeRoulette = (execute: boolean): void => {
    if (execute) {
        roulette.value?.cycleItems()
    }
}

onMounted(() => {
    store.filters = props.filters
    listOptions.value = props.options
})
</script>

<template>
    <Head title="Home" />
    <Toaster />
    <div class="bg-gray-50 text-black/50 dark:bg-inherit dark:text-white/50">
        <div class="py-8 px-16 h-screen">
            <div class="grid grid-cols-4 gap-2 h-full">
                <RouletteBoxes
                    :options="allOptions"
                    class="col-span-3"
                    ref="roulette"/>
                <List
                    class="col-span-1"
                    :default-options="listOptions.join('\n')"
                    @list-update="listUpdate"
                    @execute-roulette="executeRoulette">
                </List>
            </div>
        </div>
    </div>
</template>
 