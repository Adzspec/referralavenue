<template>
    <n-modal style="width: 600px" :show="show" title="Edit Store" preset="card" @close="emit('close')">
        <n-form ref="formRef" :model="form" :rules="rules" label-placement="top" class="mt-4">
            <n-grid x-gap="12" :cols="2">
                <n-gi>
                    <n-form-item label="Name" path="name">
                        <n-input v-model:value="form.name" placeholder="Enter store name" />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Image URL" path="image">
                        <n-input v-model:value="form.image" placeholder="Optional image URL" />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Description" path="description">
                        <n-input type="textarea" v-model:value="form.description" placeholder="Short description" />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Status" path="status">
                        <n-select v-model:value="form.status" :options="statusOptions" />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Channel ID" path="channelId">
                        <n-input-number v-model:value="form.channelId" clearable />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Channel Name" path="channelName">
                        <n-input v-model:value="form.channelName" />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Program ID" path="programId">
                        <n-input-number v-model:value="form.programId" clearable />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Category ID" path="categoryId">
                        <n-input-number v-model:value="form.categoryId" clearable />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Category Name" path="categoryName">
                        <n-input v-model:value="form.categoryName" />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Product Feed ID" path="productFeedId">
                        <n-input-number v-model:value="form.productFeedId" clearable />
                    </n-form-item>
                </n-gi>
            </n-grid>
        </n-form>

        <template #action>
            <n-space justify="end">
                <n-button @click="emit('close')">Cancel</n-button>
                <n-button type="primary" @click="submit">Update</n-button>
            </n-space>
        </template>
    </n-modal>
</template>

<script setup lang="ts">
import type { FormInst, FormRules } from 'naive-ui';
import { ref, watch } from 'vue';

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'submit', payload: Record<string, any>): void;
}>();

const props = defineProps<{
    show: boolean;
    store: Record<string, any> | null;
}>();

const formRef = ref<FormInst | null>(null);

const form = ref({
    name: '',
    image: '',
    description: '',
    status: 1,
    channelId: null,
    channelName: '',
    programId: null,
    categoryId: null,
    categoryName: '',
    productFeedId: null,
});

const rules: FormRules = {
    name: [{ required: true, message: 'Store name is required', trigger: ['blur'] }],
};

const statusOptions = [
    { label: 'Active', value: 1 },
    { label: 'Inactive', value: 2 },
];

watch(
    () => props.store,
    (store) => {
        if (store) {
            Object.assign(form.value, store);
        }
    },
    { immediate: true },
);

const submit = async () => {
    if (!formRef.value) return;
    try {
        await formRef.value.validate();
        emit('submit', form.value);
    } catch (err) {
        // validation failed
    }
};
</script>
