<template>
    <n-modal style="width: 600px" :show="show" title="Edit Company" preset="card" @close="emit('close')">
        <n-form ref="formRef" :model="form" :rules="rules" label-placement="top">
            <n-grid :x-gap="12" :y-gap="8" :cols="2">
                <n-grid-item>
                    <n-form-item label="Name" path="name">
                        <n-input v-model:value="form.name" />
                    </n-form-item>
                </n-grid-item>
                <n-grid-item>
                    <n-form-item label="Email" path="email">
                        <n-input v-model:value="form.email" />
                    </n-form-item>
                </n-grid-item>
                <n-grid-item>
                    <n-form-item label="Domain" path="domain">
                        <n-input v-model:value="form.domain" />
                    </n-form-item>
                </n-grid-item>
                <n-grid-item>
                    <n-form-item label="Registration No" path="registration_no">
                        <n-input v-model:value="form.registration_no" />
                    </n-form-item>
                </n-grid-item>
                <n-grid-item>
                    <n-form-item label="VAT" path="vat">
                        <n-input v-model:value="form.vat" />
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
            <n-button @click="emit('close')">Cancel</n-button>
            <n-button type="primary" :loading="loading" @click="submit">Update</n-button>
        </div>
    </n-modal>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import {
    NModal,
    NForm,
    NFormItem,
    NInput,
    NSwitch,
    NButton,
    useMessage,
    FormRules
} from 'naive-ui';

const props = defineProps<{
    show: boolean;
    company: any;
}>();

const emit = defineEmits(['close']);

const form = ref({ ...props.company });

watch(
    () => props.company,
    (val) => {
        form.value = { ...val };
    },
    { immediate: true },
);

const message = useMessage();
const loading = ref(false);

const rules: FormRules = {
    name: [{ required: true, message: 'Name is required', trigger: 'blur' }],
    email: [
        { required: true, message: 'Email is required', trigger: 'blur' },
        { type: 'email', message: 'Invalid email format', trigger: ['blur', 'input'] },
    ],
    domain: [{ required: true, message: 'Domain is required', trigger: 'blur' }],
    registration_no: [{ required: true, message: 'Registration No is required', trigger: 'blur' }],
    vat: [{ required: true, message: 'VAT is required', trigger: 'blur' }],
};

const formRef = ref();

const submit = () => {
    formRef.value?.validate((errors: any) => {
        if (!errors) {
            loading.value = true;
            router.put(`/companies/${form.value.id}`, form.value, {
                onSuccess: () => {
                    message.success('Company updated successfully');
                    emit('close');
                },
                onError: () => {
                    message.error('Update failed');
                },
                onFinish: () => {
                    loading.value = false;
                },
            });
        }
    });
};

</script>
