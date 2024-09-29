<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref } from 'vue';
import TagsInput from '../ui/tags-input/TagsInput.vue';
import FilterSheet, { type Filter } from './FilterSheet.vue';
import TagsInputItem from '../ui/tags-input/TagsInputItem.vue';
import TagsInputClickable from './Input/TagsInputClickable.vue';
import { useLoading } from '../Composables/useLoading';
import Skeleton from '../ui/skeleton/Skeleton.vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage()

const disabledFilters = ref<Filter[]>([])
const enabledFilters = ref<Filter[]>([])

const { isLoading, startLoading, stopLoading } = useLoading(false);

const allFilters = computed((): Filter[] => enabledFilters.value.concat(disabledFilters.value))

const switchFilters = (index: number, filterToSplice: string, filterToPush: string) => {
    const filters = {
        disabledFilters,
        enabledFilters
    }

    let pushKey =  filterToPush as keyof typeof filters
    let spliceKey =  filterToSplice as keyof typeof filters

    filters[pushKey].value.push(filters[spliceKey].value[index])
    filters[spliceKey].value.splice(index, 1)
}

const addNewFilter = async (filter: Filter): Promise<void> => {
    
    // disabledFilters.value.push(filter)
    // console.log(disabledFilters.value)
    // toast success?
}

onMounted(async () => {
    startLoading()

    fetch('/filters', { method: 'get' })
        .then(resp => resp.json())
        .then(({data}) => {
            enabledFilters.value = data.enabled
            disabledFilters.value = data.disabled
        })
        .catch(err => console.log(err))
        .finally(() => stopLoading())
})

onUnmounted(() => {
    const _token: string = (page.props.csrf_token as string)

    fetch('/filters', { 
        method: 'put',
        headers: { 
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': _token
        },
        body: JSON.stringify({
            enabled: enabledFilters.value,
            disabled: disabledFilters.value
        })
    })
})
</script>

<template>
    <div class="pb-4">
        <template v-if="isLoading">
            <div class="flex justify-center items-center flex-col">
                <Skeleton class="h-5 w-1/3 rounded-xl" />
                <Skeleton class="h-20 w-full rounded-xl mt-2" />

                <Skeleton class="h-5 w-1/3 rounded-xl mt-4" />
                <Skeleton class="h-20 w-full rounded-xl mt-2" />
            </div>
        </template>
        <template v-else>
            <div class="pb-4">
                <p class="text-center pb-2">All Filters</p>
                <TagsInput :model-value="disabledFilters">
                    <p v-if="disabledFilters.length === 0" class="text-gray-400">No Filters To Add</p>
                    <template v-else>
                        <TagsInputItem v-for="(item, index) in disabledFilters" :key="index" :value="item" class="hover:bg-primary">
                            <TagsInputClickable :text="item.name" :index="index" @clicked="(i) => switchFilters(i, 'disabledFilters', 'enabledFilters')" />
                        </TagsInputItem>
                    </template>
                </TagsInput>
            </div>
            
            <div>
                <p class="text-center pb-2">Enabled Filters</p>
                <TagsInput :model-value="enabledFilters">
                    <p v-if="enabledFilters.length === 0" class="text-gray-400">No Filters Selected</p>
                    <template v-else>
                        <TagsInputItem v-for="(item, index) in enabledFilters" :key="index" :value="item" class="hover:bg-red-600">
                            <TagsInputClickable :text="item.name" :index="index" @clicked="(i) => switchFilters(i, 'enabledFilters', 'disabledFilters')" />
                            </TagsInputItem>
                    </template>                
                </TagsInput>
            </div>
        </template>  
    </div>
    
    <FilterSheet :filters="allFilters" @add-new-filter="addNewFilter"></FilterSheet>
</template>