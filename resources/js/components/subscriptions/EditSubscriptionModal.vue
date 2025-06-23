<template>
    <n-modal style="width: 600px" :show="show" title="Edit Subscription" preset="card" @close="$emit('close')">
        <n-form ref="formRef" :model="form" :rules="rules" label-placement="top">
            <n-grid :x-gap="12" :y-gap="8" :cols="2">
                <n-grid-item>
                    <n-form-item label="Name" path="name">
                        <n-input v-model:value="form.name" />
                    </n-form-item>
                </n-grid-item>
                <n-grid-item>
                    <n-form-item label="Price" path="price">
                        <n-input v-model:value="form.price" type="number" />
                    </n-form-item>
                </n-grid-item>
                <n-grid-item>
                    <n-form-item label="Duration" path="duration">
                        <n-input v-model:value="form.duration" />
                    </n-form-item>
                </n-grid-item>
                <n-grid-item>
                    <n-form-item label="Status">
                        <n-switch v-model:value="form.status" :checked-value="1" :unchecked-value="0" />
                    </n-form-item>
                </n-grid-item>
            </n-grid>
        </n-form>

        <div class="mt-4 flex justify-end gap-2">
            <n-button @click="$emit('close')">Cancel</n-button>
            <n-button type="primary" :loading="loading" @click="submit">Update</n-button>
        </div>
    </n-modal>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { NModal, NForm, NFormItem, NInput, NSwitch, NButton, useMessage, FormRules } from 'naive-ui';

const props = defineProps<{ show: boolean; subscription: any }>();
const emit = defineEmits(['close']);

const form = ref({ ...props.subscription });

watch(() => props.subscription, val => {
    form.value = { ...val };
}, { immediate: true });

const rules: FormRules = {
    name: [{ required: true, message: 'Name is required', trigger: 'blur' }],
    price: [{ required: true, message: 'Price is required', trigger: 'blur' }],
    duration: [{ required: true, message: 'Duration is required', trigger: 'blur' }]
};

const formRef = ref();
const message = useMessage();
const loading = ref(false);

const submit = () => {
    formRef.value?.validate((err: any) => {
        if (!err) {
            loading.value = true;
            router.put(`/subscriptions/${form.value.id}`, form.value, {
                onSuccess: () => {
                    message.success('Subscription updated');
                    emit('close');
                },
                onError: () => {
                    message.error('Failed to update');
                },
                onFinish: () => {
                    loading.value = false;
                }
            });
        }
    });
};
</script>
