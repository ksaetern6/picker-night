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
    defaultFilter: string
}

interface FilterSelectEmits {
    (event: 'updateActiveSelection', payload: Filter): void
}

const props = withDefaults(defineProps<FilterSelectProps>(), {
    defaultFilter: ''
})

const emits = defineEmits<FilterSelectEmits>()

const emitSelection = (value: string) => {
    const filter = props.selections.find(selection => selection.name === value)
    
    if (filter) {
        emits('updateActiveSelection', filter)
    }
}
</script>
<template>
    <Select @update:model-value="(value) => emitSelection(value)" :default-value="defaultFilter">
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