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
                        <n-input-number :min="0" v-model:value="form.price">
                        <template #prefix>
                            $
                        </template>
                        </n-input-number>
                    </n-form-item>
                </n-grid-item>
                <n-grid-item>
                    <n-form-item label="Duration (in days)" path="duration">
                        <n-input-number v-model:value="form.duration" placeholder="In days,e.g., 30,60,90,120,365" />
                    </n-form-item>
                </n-grid-item>
                <n-grid-item>
                    <n-form-item label="Stripe Price Id" path="price_id">
                        <n-input v-model:value="form.price_id" />
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

watch(
    () => props.subscription,
    (val) => {
        form.value = {
            ...val,
            price: parseFloat(val.price), // <- convert string to number
            duration: parseInt(val.duration), // <- ensure number
            status: Number(val.status), // <- 0/1 as number
        };
    },
    { immediate: true }
);

const rules: FormRules = {
    name: [
        {
            required: true,
            message: 'Name is required',
            trigger: ['input', 'blur']
        }
    ],
    price: [
        {
            required: true,
            type: 'number',
            message: 'Price is required and must be a number',
            trigger: ['input', 'blur']
        },
        {
            type: 'number',
            min: 0,
            message: 'Minimum price is $0',
            trigger: ['input', 'blur']
        }
    ],
    duration: [
        {
            required: true,
            type: 'number',
            message: 'Duration is required and must be a number',
            trigger: ['input', 'blur']
        },
        {
            type: 'number',
            min: 1,
            message: 'Duration must be at least 1 day',
            trigger: ['input', 'blur']
        }
    ],
    price_id: [
        {
            required: true,
            message: 'Price Id is required',
            trigger: ['input', 'blur']
        }
    ],
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
