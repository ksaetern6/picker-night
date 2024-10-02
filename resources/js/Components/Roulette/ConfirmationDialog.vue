<script setup lang="ts">
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'
import Separator from '../ui/separator/Separator.vue';

interface ConfirmationDialogProps {
    open: boolean,
    title: string,
}

interface ConfirmationDialogEmits {
    (event: 'toggleDialog', payload: boolean): void
}

const props = defineProps<ConfirmationDialogProps>()
const emit = defineEmits<ConfirmationDialogEmits>()
</script>

<template>
  <Dialog :open="open" @update:open="() => emit('toggleDialog', !open)">
    <DialogContent>
      <DialogHeader>
        <DialogTitle class="text-center">{{ title }}</DialogTitle>
        <Separator class="my-2"/>
        <DialogDescription>
          Make changes to your profile here. Click save when you're done.
        </DialogDescription>
      </DialogHeader>

      <DialogFooter>
        Notify the bois?
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>