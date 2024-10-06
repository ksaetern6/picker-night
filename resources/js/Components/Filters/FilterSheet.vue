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
import { nextTick, ref } from 'vue';
import Input from '../ui/input/Input.vue';
import { usePickerStore } from '@/store/store';
import { useToast } from '@/Components/ui/toast/use-toast'

export interface Filter {
  id: string | null
  enabled: boolean
  name: string
  options: string[]
}

const { toast } = useToast()
const store = usePickerStore()

const textArea = ref<string>('')
const newFilter = ref<string>('')
const filterInputActive = ref<boolean>(false)
const activeFilter = ref<Filter | null>();
const activeIndex = ref<number | null>(null);
const filterInputRef = ref<InstanceType<typeof Input> | null>(null)

const changeActiveFilter = ({ filter, index }: {filter: Filter, index: number}): void => {
  activeFilter.value = filter
  activeIndex.value = index
  textArea.value = filter.options.join('\n')
}

const enableFilterInput = (): void => {
  filterInputActive.value = true
  newFilter.value = ''
  textArea.value = ''
  activeFilter.value = null

  nextTick(() => {
    filterInputRef.value?.inputRef?.focus()
  })
}

const saveChanges = async (): Promise<void> => {
  const filter = {
    id: activeFilter.value?.id ?? null,
    enabled: activeFilter.value?.enabled ?? false,
    name: activeFilter.value?.name ?? newFilter.value,
    options: textArea.value.split('\n').filter((f) => f.trim() !== '')
  }

  // new filter
  if (!activeFilter.value) {
    !await store.addFilter(filter)
      ? toast({ title: 'Error adding Filter', description: 'There was an error adding the filter', variant: 'destructive', duration: 2000})
      : toast({ title: 'Filter Added', description: 'Filter Successfully Added', duration: 2000 })
    
    filterInputActive.value = false
    textArea.value = ''
  } else if (activeIndex.value !== null && filter?.id) {
    // updating filter
    store.updateFilterById(filter, filter.id)
  }
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
          @keyup.enter="() => {}"/>
        <FilterSelect v-else :selections="store.filters" @update-active-selection="changeActiveFilter" />
        <Textarea class="h-96 mt-4" placeholder="Add options to exclude" v-model="textArea" />
      </div>
      <SheetFooter class="pt-4">
        <div class="grid grid-row-2 gap-4 w-full">
          <div class="flex flex-row justify-evenly">
            <Button variant="secondary" :onclick="enableFilterInput" :disabled="filterInputActive">
              Add Filter
            </Button>
            <Button type="submit" :onclick="saveChanges">
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