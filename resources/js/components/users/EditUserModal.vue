<script setup lang="ts">
import { computed, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useMessage, NModal, NForm, NInput, NFormItem, NButton } from 'naive-ui';

const props = defineProps<{
    show: boolean;
    user: {
        id: number;
        name: string;
        email: string;
        roles?: { name: string }[];
    } | null;
    roles: { id: number; name: string }[];
}>();

const emit = defineEmits<{
    (e: 'update:show', value: boolean): void;
    (e: 'close'): void;
}>();
const localShow = computed({
    get: () => props.show,
    set: (val: boolean) => emit('update:show', val),
});
const handleClose = () => {
    emit('update:show', false);
    emit('close');
};
const message = useMessage();

const form = useForm({
    name: '',
    email: '',
    roles: [] as number[],
});

watch(() => props.user, (user) => {
    if (user) {
        form.name = user.name;
        form.email = user.email;
        form.roles = props.roles
            .filter(role => user.roles?.some(r => r.name === role.name))
            .map(role => role.id);
    }
},{ immediate: true });

const submit = () => {
    if (!props.user) return;
    form.put(route('users.update', props.user.id), {
        preserveScroll: true,
        onSuccess: () => {
            message.success('User updated successfully');
            emit('close');
        },
        onError: () => message.error('Update failed'),
    });
};
</script>

<template>
    <n-modal v-model:show="localShow" preset="dialog" @close="emit('close')"  title="Edit User" @after-leave="handleClose">
        <n-form :model="form" label-placement="top">
            <n-form-item label="Name" path="name">
                <n-input v-model:value="form.name" />
            </n-form-item>

            <n-form-item label="Email" path="email">
                <n-input v-model:value="form.email" />
            </n-form-item>

            <n-form-item label="Roles">
                <n-select
                    v-model:value="form.roles"
                    :options="props.roles.map(role => ({ label: role.name, value: role.id }))"
                    multiple
                    placeholder="Select roles"
                />
            </n-form-item>
            <n-button type="primary" @click="submit" :loading="form.processing">Update</n-button>
        </n-form>
    </n-modal>
</template>
