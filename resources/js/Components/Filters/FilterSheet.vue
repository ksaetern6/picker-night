<script setup lang="ts">
import { Button } from '@/Components/ui/button'
import {
  Sheet,
  SheetClose,
  SheetContent,
  SheetDescription,
  SheetFooter,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
} from '@/Components/ui/sheet'
import FilterSelect from './Select/FilterSelect.vue';
import Textarea from '../ui/textarea/Textarea.vue';
import { computed, nextTick, onUnmounted, ref } from 'vue';
import Input from '../ui/input/Input.vue';
import { useToast } from '../ui/toast';

interface FilterSheetProps {
  filters: Filter[]
}

export interface Filter {
  id: string | null
  name: string
  options: string[]
}

interface FilterSheetEmits {
  (event: 'addNewFilter', payload: Filter): void
}


defineProps<FilterSheetProps>()
const emit = defineEmits<FilterSheetEmits>()
const { toast } = useToast()

const activeFilter = ref<Filter>()
const newFilter = ref<string>('')
const filterInputActive = ref<boolean>(false)
const filterInputRef = ref<InstanceType<typeof Input> | null>(null)
const textArea = ref<string>('')

const activeList = computed((): string => {
  return activeFilter.value
    ? activeFilter.value.options.join('\n')
    : ''
})

const changeActiveFilter = (filter: Filter): void => {
  activeFilter.value = filter
  textArea.value = activeList.value
}

const toggleFilterInput = (): void => {
  textArea.value = ''
  filterInputActive.value = !filterInputActive.value
  if (filterInputActive.value) {
    nextTick(() => {
      filterInputRef.value?.inputRef?.focus()
    })
  }
}

const addFilter = (): void => {
  let filter: Filter = {
    id: null,
    name: newFilter.value,
    options: []
  }

  activeFilter.value = filter
  textArea.value = ''

  const nextEl = filterInputRef.value?.inputRef?.nextElementSibling as HTMLElement | null;

  // toggleFilterInput()

  nextTick(() => {
    nextEl?.focus()
  })
}

const saveList = (): void => {
  if (!activeFilter.value) return

  activeFilter.value.options = textArea.value.split('\n').filter((f) => f.trim() !== '')

  emit('addNewFilter', activeFilter.value)
}
</script>

<template>
  <Sheet>
    <SheetTrigger as-child>
      <Button class="w-full">
        Open Filters
      </Button>
    </SheetTrigger>
    <SheetContent>
      <SheetHeader>
        <SheetTitle>Add Filters To Your List</SheetTitle>
        <SheetDescription>
            Description
        </SheetDescription>
      </SheetHeader>
      <div class=py-4>
        <Input v-if="filterInputActive" 
          v-model="newFilter" 
          type="text" 
          placeholder="Enter Your Filter Name" 
          ref="filterInputRef" 
          @blur="addFilter"
          @keyup.enter="addFilter"/>
        <FilterSelect v-else :selections="filters" @update-active-selection="changeActiveFilter" :defaultFilter="activeFilter?.name"/>
        <Textarea class="h-96 mt-4" placeholder="Add options to exclude" v-model="textArea" />
      </div>
      <SheetFooter class="pt-4">
        <div class="grid grid-row-2 gap-4 w-full">
          <div class="flex flex-row justify-evenly">
            <Button variant="secondary" :onclick="toggleFilterInput">
              Add Filter
            </Button>
            <Button type="submit" :onclick="saveList">
              Save changes
            </Button>
          </div>
        <SheetClose as-child>
          <Button type="submit" variant="ghost">
            <span class="underline">Close Filters</span>
          </Button>
        </SheetClose>
        </div>
      </SheetFooter>
    </SheetContent>
  </Sheet>
</template>