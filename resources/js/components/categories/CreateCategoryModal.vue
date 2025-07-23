<template>
    <n-modal :show="show" @close="$emit('close')" preset="dialog" title="Create Category">
        <n-form ref="formRef" :model="form" :rules="rules" label-placement="top">
            <n-form-item label="Title" path="name">
                <n-input v-model:value="form.name" placeholder="Category title" />
            </n-form-item>
<!--            <n-form-item label="Parent Category" path="parent_id">-->
<!--                <n-select-->
<!--                    v-model:value="form.parent_id"-->
<!--                    :options="parentOptions"-->
<!--                    placeholder="Select parent (optional)"-->
<!--                    clearable-->
<!--                />-->
<!--            </n-form-item>-->
        </n-form>
        <template #action>
            <n-space justify="end">
                <n-button @click="$emit('close')">Cancel</n-button>
                <n-button type="primary" :loading="saving" @click="submit">Create</n-button>
            </n-space>
        </template>
    </n-modal>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import type { FormInst } from 'naive-ui';

defineProps<{
    show: boolean;
    parentCategories: { id: number; name: string }[];
}>();
const emit = defineEmits(['close', 'submit']);

const formRef = ref<FormInst | null>(null);
const saving = ref(false);

const form = ref({
    name: '',
    parent_id: null,
});

const rules = {
    name: { required: true, message: 'Name is required', trigger: ['input', 'blur'] },
};

// const parentOptions = computed(() =>
//     props.parentCategories.map(cat => ({
//         label: cat.name,
//         value: cat.id,
//     }))
// );

const submit = async () => {
    const valid = await formRef.value?.validate().catch(() => false);
    if (!valid) return;

    saving.value = true;
    try {
        emit('submit', { ...form.value });
    } finally {
        saving.value = false;
    }
};
</script>
