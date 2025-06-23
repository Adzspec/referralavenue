<script setup lang="ts">
import { NModal, NForm, NFormItem, NInput, NButton, useMessage } from 'naive-ui';
import { computed, ref } from 'vue';
import { router } from '@inertiajs/vue3';

// const props = defineProps<{
//     show: boolean;
// }>();
const props = defineProps<{
    show: boolean;
    roles: Array<{ id: number; name: string }>;
}>();
const emit = defineEmits<{
    (e: 'update:show', value: boolean): void;
    (e: 'close'): void;
}>();
const localShow = computed({
    get: () => props.show,
    set: (val: boolean) => emit('update:show', val),
});
const form = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: null as number | null,
});

const loading = ref(false);
const message = useMessage();

const handleSubmit = () => {
    loading.value = true;

    router.post('/users', form.value, {
        onSuccess: () => {
            message.success('User created successfully');
            emit('close');
        },
        onError: () => {
            message.error('There were errors creating the user');
        },
        onFinish: () => {
            loading.value = false;
        }
    });
};
const handleClose = () => {
    emit('update:show', false);
    emit('close');
};
</script>

<template>
    <n-modal style="width: 600px" v-model:show="localShow" @update:show="emit('close')" title="Create User" preset="card" :auto-focus="false" @after-leave="handleClose">
        <n-form :model="form" label-placement="top">
            <n-form-item label="Name">
                <n-input v-model:value="form.name" placeholder="Enter Name Here" />
            </n-form-item>
            <n-form-item label="Email">
                <n-input v-model:value="form.email" placeholder="Enter User E-Mail" />
            </n-form-item>
            <n-form-item label="Password">
                <n-input type="password" v-model:value="form.password" placeholder="Enter Password" />
            </n-form-item>
            <n-form-item label="Confirm Password">
                <n-input type="password" v-model:value="form.password_confirmation" placeholder="Confirm Password" />
            </n-form-item>
            <n-form-item label="Role" path="role">
                <n-select
                    v-model:value="form.role"
                    :options="props.roles.map(role => ({ label: role.name, value: role.id }))"
                    placeholder="Select a role"
                    clearable
                />
            </n-form-item>
            <div class="flex justify-end mt-4 gap-3">
                <n-button @click="emit('close')">Cancel</n-button>
                <n-button type="primary" :loading="loading" @click="handleSubmit">Create</n-button>
            </div>
        </n-form>
    </n-modal>
</template>
