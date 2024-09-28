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
import { computed, ref } from 'vue';

interface FilterSheetProps {
  filters: Filter[]
}

export interface Filter {
  id: string
  name: string
  options: string[]
}

const activeFilter = ref<Filter>()

const activeList = computed((): string => {
  return activeFilter.value
    ? activeFilter.value.options.join('\n')
    : ''
})

defineProps<FilterSheetProps>()

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
        <FilterSelect :selections="filters" @update-active-selection="(s: Filter) => activeFilter = s"/>
        <Textarea class="h-96 mt-4" placeholder="Add options to exclude" v-model="activeList" />
      </div>
      <SheetFooter class="pt-4">
        <SheetClose as-child>
          <Button type="submit">
            Save changes
          </Button>
        </SheetClose>
      </SheetFooter>
    </SheetContent>
  </Sheet>
</template>