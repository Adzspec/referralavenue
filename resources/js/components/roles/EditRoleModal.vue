<script setup lang="ts">
import { ref, watch } from 'vue';
import { NModal, NForm, NFormItem, NInput, NCheckboxGroup, NCheckbox, NButton } from 'naive-ui';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    show: boolean;
    role: {
        id: number;
        name: string;
        permissions: { name: string }[];
    };
    permissions: {
        id: number;
        name: string;
    }[];
}>();

const emit = defineEmits(['close']);

const name = ref('');
const selectedPermissions = ref<number[]>([]);

watch(
    () => props.role,
    (newVal) => {
        if (newVal) {
            name.value = newVal.name;
            selectedPermissions.value = props.permissions
                .filter(p => props.role.permissions.map(rp => rp.name).includes(p.name))
                .map(p => p.id);
        }
    },
    { immediate: true }
);

const submit = () => {
    router.put(`/roles/${props.role.id}`, {
        name: name.value,
        permissions: selectedPermissions.value,
    }, {
        onSuccess: () => emit('close'),
    });
};
</script>
<template>
    <n-modal :show="show" @update:show="emit('close')" preset="dialog" title="Edit Role">
        <n-form @submit.prevent="submit">
            <n-form-item label="Role Name">
                <n-input v-model:value="name" placeholder="Enter role name" />
            </n-form-item>

            <n-form-item label="Permissions">
                <n-checkbox-group v-model:value="selectedPermissions">
                    <div class="grid grid-cols-2 gap-2">
                        <n-checkbox
                            v-for="permission in permissions"
                            :key="permission.id"
                            :value="permission.id"
                            :label="permission.name"
                        />
                    </div>
                </n-checkbox-group>
            </n-form-item>

            <div class="flex justify-end gap-2 pt-4">
                <n-button type="default" @click="emit('close')">Cancel</n-button>
                <n-button type="primary" attr-type="submit">Update</n-button>
            </div>
        </n-form>
    </n-modal>
</template>

