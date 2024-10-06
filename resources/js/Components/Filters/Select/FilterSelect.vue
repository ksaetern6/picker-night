<script setup lang="ts">
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/Components/ui/select'
import type { Filter } from '../FilterSheet.vue';

interface FilterSelectProps {
    selections: Filter[]
    // defaultFilter: string
}

interface FilterSelectEmits {
    (event: 'updateActiveSelection', payload: { filter: Filter, index: number}): void
}

const props = withDefaults(defineProps<FilterSelectProps>(), {
    // defaultFilter: ''
})

const emit = defineEmits<FilterSelectEmits>()

const emitSelection = (value: string) => {
    const filterIndex = props.selections.findIndex(selection => selection.name === value)
    
    if (filterIndex > -1) {
        emit('updateActiveSelection', {
            filter: props.selections[filterIndex],
            index: filterIndex
        })
    }
}
</script>
<template>
    <!-- <Select @update:model-value="(value) => emitSelection(value)" :default-value="defaultFilter"> -->
    <Select @update:model-value="(value) => emitSelection(value)">
        <SelectTrigger>
            <SelectValue placeholder="Select A Filter"></SelectValue>
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectItem v-for="(selection, index) in selections" 
                    :value="selection.name"
                    :key="index">
                    {{ selection.name }}
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>