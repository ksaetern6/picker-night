<script setup lang="ts">
import Input from '@/Components/ui/input/Input.vue';
import { Copy } from 'lucide-vue-next';
import { ref } from 'vue';
import { useToast } from '../ui/toast';

const code = ref<string>('******')
const copySuccess = ref<boolean>(false)
const { toast } = useToast()

const copyCode = async (): Promise<void> => {
    code.value = 'abc123'
    await navigator.clipboard.writeText(code.value);
    copySuccess.value = true
    toast({ title: 'Code Saved!', description: "Your code has been saved, make sure not to lose this code.", duration: 4000 })
}
</script>
<template>
    <div>
        <div class="relative">
            <Input class="rounded-xl text-xl text-center pr-16" 
                :class="{ 'border-green-400 ring-offset-2': copySuccess}"
                type="text" 
                readonly 
                :model-value="code" />
            <span class="rounded-r-xl bg-white absolute inset-y-0 right-0 flex px-4">
                <Copy class="cursor-pointer my-auto text-secondary" 
                    :onclick="copyCode" />
            </span>
        </div>
        <p class="text-center text-muted-foreground text-sm pt-2">
            Save your list by copying the list code, you can share this list with your friends.
        </p>
    </div>
</template>