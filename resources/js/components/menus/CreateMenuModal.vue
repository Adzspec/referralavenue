<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { NModal, NForm, NFormItem, NInput, NSelect, NButton, useMessage } from 'naive-ui'

const props = defineProps<{
    show: boolean
    parentMenus: { id: number, title: string }[]
}>()

const emit = defineEmits(['close'])
const message = useMessage()

const form = ref({
    title: '',
    href: '',
    icon: '',
    role: '',
    permission: '',
    parent_id: null,
    order: 0
})

const loading = ref(false)

const handleSubmit = () => {
    loading.value = true
    router.post('/menus', form.value, {
        onSuccess: () => {
            message.success('Menu created successfully')
            emit('close')
        },
        onFinish: () => loading.value = false
    })
}
</script>

<template>
    <NModal :show="props.show" preset="dialog" title="Add Menu" @close="emit('close')">
        <NForm label-width="100">
            <NFormItem label="Title">
                <NInput v-model:value="form.title" placeholder="Enter title" />
            </NFormItem>
            <NFormItem label="Href">
                <NInput v-model:value="form.href" placeholder="/dashboard" />
            </NFormItem>
            <NFormItem label="Icon">
                <NInput v-model:value="form.icon" placeholder="e.g. home, users" />
            </NFormItem>
            <NFormItem label="Role">
                <NInput v-model:value="form.role" placeholder="Role name" />
            </NFormItem>
            <NFormItem label="Permission">
                <NInput v-model:value="form.permission" placeholder="Permission name" />
            </NFormItem>
            <NFormItem label="Parent Menu">
                <NSelect v-model:value="form.parent_id" :options="parentMenus.map(m => ({ label: m.title, value: m.id }))" clearable />
            </NFormItem>
            <NFormItem label="Order">
                <NInputNumber v-model:value="form.order" type="number" placeholder="0" />
            </NFormItem>
            <div class="flex justify-end gap-2 mt-4">
                <NButton @click="emit('close')">Cancel</NButton>
                <NButton type="primary" :loading="loading" @click="handleSubmit">Save</NButton>
            </div>
        </NForm>
    </NModal>
</template>
