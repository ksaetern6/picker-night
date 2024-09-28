<script setup lang="ts">

interface TagsInputClickableProps {
    index: number
    text: string
}

interface TagsInputClickableEmitEvents {
    (event: 'clicked', payload: number): void
}

defineProps<TagsInputClickableProps>()
const emit = defineEmits<TagsInputClickableEmitEvents>()

</script>
<template>
    <div class="m-4 cursor-pointer" :onclick="() => emit('clicked', index)">
        <span>{{ text }}</span>
    </div>
</template>