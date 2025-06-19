<script setup lang="ts">
import { ref } from 'vue';
import { NModal, NCard, NForm, NFormItem, NInput, NButton, NCheckboxGroup, NCheckbox, useMessage } from 'naive-ui';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    show: boolean;
    permissions: { id: number; name: string }[];
}>();

const emit = defineEmits(['close']);

const form = ref({
    name: '',
    permissions: [] as number[],
});

const loading = ref(false);
const message = useMessage();

const handleSubmit = () => {
    loading.value = true;
    router.post('/roles', form.value, {
        onSuccess: () => {
            message.success('Role created successfully');
            emit('close');
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};
</script>

<template>
    <n-modal :show="props.show" @update:show="emit('close')" preset="dialog" title="Create Role">
        <n-card>
            <n-form label-placement="top" class="space-y-4">
                <n-form-item label="Role Name">
                    <n-input v-model:value="form.name" placeholder="Enter role name" />
                </n-form-item>

                <n-form-item label="Permissions">
                    <n-checkbox-group v-model:value="form.permissions">
                        <div class="grid grid-cols-2 gap-2">
                            <n-checkbox
                                v-for="permission in props.permissions"
                                :key="permission.id"
                                :value="permission.id"
                                :label="permission.name"
                            />
                        </div>
                    </n-checkbox-group>
                </n-form-item>

                <div class="flex justify-end gap-2 mt-4">
                    <n-button @click="emit('close')">Cancel</n-button>
                    <n-button type="primary" :loading="loading" @click="handleSubmit">Create</n-button>
                </div>
            </n-form>
        </n-card>
    </n-modal>
</template>
