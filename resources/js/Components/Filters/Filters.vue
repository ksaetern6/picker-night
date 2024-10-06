<script setup lang="ts">
import TagsInput from '../ui/tags-input/TagsInput.vue';
import FilterSheet, { type Filter } from './FilterSheet.vue';
import TagsInputItem from '../ui/tags-input/TagsInputItem.vue';
import TagsInputClickable from './Input/TagsInputClickable.vue';
import { useLoading } from '../Composables/useLoading';
import Skeleton from '../ui/skeleton/Skeleton.vue';
import { useToast } from '../ui/toast';
import { usePickerStore } from '@/store/store';

const store = usePickerStore()

const { isLoading, startLoading, stopLoading } = useLoading(false);

const switchFilters = (index: number, filterToSwitchFrom: string) => {
    const filter = filterToSwitchFrom === 'disabled'
        ? store.disabledFilters[index]
        : store.enabledFilters[index]

    if (filter.id) {
        store.switchFiltersById(filter.id)
    }
}
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
                <TagsInput :model-value="store.disabledFilters">
                    <p v-if="store.disabledFilters.length === 0" class="text-gray-400">No Filters To Add</p>
                    <template v-else>
                        <TagsInputItem v-for="(item, index) in store.disabledFilters" :key="index" :value="item" class="hover:bg-primary">
                            <TagsInputClickable :text="item.name" :index="index" @clicked="(i) => switchFilters(i, 'disabled')" />
                        </TagsInputItem>
                    </template>
                </TagsInput>
            </div>
            
            <div>
                <p class="text-center pb-2">Enabled Filters</p>
                <TagsInput :model-value="store.enabledFilters">
                    <p v-if="store.enabledFilters.length === 0" class="text-gray-400">No Filters Selected</p>
                    <template v-else>
                        <TagsInputItem v-for="(item, index) in store.enabledFilters" :key="index" :value="item" class="hover:bg-red-600">
                            <TagsInputClickable :text="item.name" :index="index" @clicked="(i) => switchFilters(i, 'enabled')" />
                            </TagsInputItem>
                    </template>                
                </TagsInput>
            </div>
        </template>  
    </div>
    
    <FilterSheet></FilterSheet>
</template>