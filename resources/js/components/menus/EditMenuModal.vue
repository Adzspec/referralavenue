<template>
    <n-modal v-model:show="show" title="Edit Menu" preset="dialog" @close="onClose">
        <n-form :model="form" :rules="rules" ref="formRef">
            <n-form-item label="Title" path="title">
                <n-input v-model:value="form.title" />
            </n-form-item>

            <n-form-item label="Href" path="href">
                <n-input v-model:value="form.href" />
            </n-form-item>

            <n-form-item label="Icon" path="icon">
                <n-input v-model:value="form.icon" />
            </n-form-item>

            <n-form-item label="Role" path="role">
                <n-input v-model:value="form.role" />
            </n-form-item>

            <n-form-item label="Permission" path="permission">
                <n-input v-model:value="form.permission" />
            </n-form-item>

            <n-form-item label="Parent Menu" path="parent_id">
                <n-select
                    v-model:value="form.parent_id"
                    :options="parentOptions"
                    placeholder="Select Parent Menu (optional)"
                    clearable
                />
            </n-form-item>

            <n-form-item label="Order" path="order">
                <n-input-number v-model:value="form.order" :min="0" />
            </n-form-item>

            <div class="flex justify-end gap-2 mt-4">
                <n-button @click="emit('close')">Cancel</n-button>
                <n-button type="primary" @click="submit">Update</n-button>
            </div>
        </n-form>
    </n-modal>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { NForm, NFormItem, NInput, NButton, NModal, NSelect, NInputNumber, FormRules, SelectOption } from 'naive-ui';

const props = defineProps<{
    show: boolean;
    menu: any;
    parentOptions: SelectOption[];
}>();

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'updated'): void;
}>();

const show = ref(props.show);
watch(() => props.show, (val) => (show.value = val));

const form = useForm({
    id: null,
    title: '',
    href: '',
    icon: '',
    role: '',
    permission: '',
    parent_id: null,
    order: 0,
});

const formRef = ref();

const rules: FormRules = {
    title: { required: true, message: 'Title is required', trigger: 'blur' },
    order: { type: 'number', required: true, message: 'Order is required', trigger: 'change' },
};

watch(
    () => props.menu,
    (val) => {
        if (val) {
            form.id = val.id;
            form.title = val.title;
            form.href = val.href;
            form.icon = val.icon;
            form.role = val.role;
            form.permission = val.permission;
            form.parent_id = val.parent_id;
            form.order = val.order;
        }
    },
    { immediate: true }
);

const submit = () => {
    form.put(`/menus/${form.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            emit('updated');
            emit('close');
        },
    });
};

</script>
