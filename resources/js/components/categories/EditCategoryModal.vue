<template>
    <n-modal :show="show" title="Edit Category" @close="emit('close')" preset="dialog" class="max-w-lg w-full">
        <n-form
            ref="formRef"
            :model="form"
            :rules="rules"
            label-placement="top"
            class="mt-4"
        >
            <n-form-item label="Name" path="name">
                <n-input v-model:value="form.name" placeholder="Enter category name" />
            </n-form-item>

            <n-form-item label="Parent Category" path="parent_id">
                <n-select
                    v-model:value="form.parent_id"
                    :options="parentCategories"
                    label-field="name"
                    value-field="id"
                    placeholder="Select parent category (optional)"
                    clearable
                />
            </n-form-item>
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
import { ref, watch } from 'vue';
import type { FormInst, FormRules } from 'naive-ui';

const props = defineProps<{
    show: boolean;
    category: {
        id: number;
        name: string;
        parent_id: number | null;
    } | null;
    parentCategories: {
        id: number;
        name: string;
    }[];
}>();

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'submit', payload: { name: string; parent_id: number | null }): void;
}>();

const formRef = ref<FormInst | null>(null);

const form = ref({
    name: '',
    parent_id: null as number | null,
});

watch(
    () => props.category,
    (category) => {
        if (category) {
            form.value.name = category.name;
            form.value.parent_id = category.parent_id;
        }
    },
    { immediate: true }
);

const rules: FormRules = {
    name: [
        { required: true, message: 'Name is required', trigger: ['blur', 'input'] }
    ]
};

const submit = async () => {
    if (!formRef.value) return;

    try {
        await formRef.value.validate();
        emit('submit', {
            name: form.value.name,
            parent_id: form.value.parent_id,
        });
    } catch (err) {
        // validation failed
    }
};
</script>
