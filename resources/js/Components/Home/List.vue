<script setup lang="ts">

import Card from '@/Components/ui/card/Card.vue';
import CardDescription from '@/Components/ui/card/CardDescription.vue';
import CardContent from '@/Components/ui/card/CardContent.vue';
import CardTitle from '@/Components/ui/card/CardTitle.vue';
import CardHeader from '@/Components/ui/card/CardHeader.vue';
import Textarea from '@/Components/ui/textarea/Textarea.vue';
import { computed, onMounted, ref, watch } from 'vue';
import Button from '../ui/button/Button.vue';
import Tabs from '../ui/tabs/Tabs.vue';
import TabsList from '../ui/tabs/TabsList.vue';
import TabsTrigger from '../ui/tabs/TabsTrigger.vue';
import TabsContent from '../ui/tabs/TabsContent.vue';

import Filters from '../Filters/Filters.vue';
import InputIcon from '../InputIcon/InputIcon.vue';

interface ListProps {
    defaultOptions: string
}

interface ListEmitEvents {
    (event: 'listUpdate', payload: string[]): void
    (event: 'executeRoulette', payload: boolean): void
}

const props = defineProps<ListProps>()
const emit = defineEmits<ListEmitEvents>()

const activeTab = ref<string>('edit-options')

let timeout: ReturnType<typeof setTimeout>

const list = computed({
    get() {
        return props.defaultOptions.length != 0 ? props.defaultOptions : ''
    },
    set(newList) {
        clearTimeout(timeout)

        timeout = setTimeout(() => {
            emit('listUpdate', newList.split('\n'))
        }, 1000)
    }
})

</script>

<template>
    <Card class="p-6">
        <CardHeader>
            <CardTitle>Randomizer</CardTitle>
            <CardDescription>Put your list below with each option per line</CardDescription>
        </CardHeader>
        <CardContent class="py-4">
            <Tabs default-value="edit-options" class="pb-2" v-model="activeTab"> 
                <TabsList class="grid w-full grid-cols-2">
                    <TabsTrigger value="edit-options">
                        Edit Options
                    </TabsTrigger>
                    <TabsTrigger value="edit-filters">
                        Filters
                    </TabsTrigger>
                </TabsList>
                <TabsContent value="edit-options">
                    <Textarea class="h-96" placeholder="Add your list here" v-model="list" />
                    <Button class="w-full mt-4" :onclick="() => emit('executeRoulette', true)">Pick An Option!</Button>
                    <InputIcon class="mt-4 w-9/12 mx-auto"/>
                </TabsContent>
                <TabsContent value="edit-filters">
                    <Filters />
                </TabsContent>
            </Tabs>
        </CardContent>
    </Card>
</template>