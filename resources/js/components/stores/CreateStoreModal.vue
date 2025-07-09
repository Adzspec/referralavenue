<template>
    <n-modal :show="show" title="Create Store" preset="dialog" @close="emit('close')">
        <n-form
            ref="formRef"
            :model="form"
            :rules="rules"
            label-placement="top"
            class="mt-4"
        >
            <n-form-item label="Name" path="name">
                <n-input v-model:value="form.name" placeholder="Enter store name" />
            </n-form-item>

            <n-form-item label="Image URL" path="image">
                <n-input v-model:value="form.image" placeholder="Optional image URL" />
            </n-form-item>

            <n-form-item label="Description" path="description">
                <n-input type="textarea" v-model:value="form.description" placeholder="Short description" />
            </n-form-item>

            <n-form-item label="Status" path="status">
                <n-select v-model:value="form.status" :options="statusOptions" />
            </n-form-item>

        </n-form>

        <template #action>
            <n-space justify="end">
                <n-button @click="emit('close')">Cancel</n-button>
                <n-button type="primary" @click="submit">Create</n-button>
            </n-space>
        </template>
    </n-modal>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import type { FormInst, FormRules } from 'naive-ui';

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'submit', payload: Record<string, any>): void;
}>();

const props = defineProps<{
    show: boolean;
}>();

const formRef = ref<FormInst | null>(null);

const form = ref({
    name: '',
    image: '',
    description: '',
    status: 1,
    channel_id: null,
    channel_name: '',
    program_id: null,
    category_id: null,
    category_name: '',
    product_feed_id: null,
});

const rules: FormRules = {
    name: [{ required: true, message: 'Store name is required', trigger: ['blur'] }],
};

const statusOptions = [
    { label: 'Active', value: 1 },
    { label: 'Inactive', value: 2 }
];

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
