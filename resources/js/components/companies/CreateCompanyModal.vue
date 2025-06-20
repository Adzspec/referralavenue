<template>
    <n-modal style="width: 600px" :show="show" title="Add Company" preset="card" @close="emit('close')">
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
                        <n-switch v-model:value="form.status" />
                    </n-form-item>
                </n-grid-item>
            </n-grid>
        </n-form>


        <div class="mt-4 flex justify-end gap-2">
            <n-button @click="emit('close')">Cancel</n-button>
            <n-button type="primary" :loading="loading" @click="submit">Save</n-button>
        </div>
    </n-modal>
</template>

<script setup lang="ts">
import { ref } from 'vue';
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

defineProps<{
    show: boolean;
}>();

const emit = defineEmits(['close']);

const form = ref({
    name: '',
    email: '',
    domain: '',
    registration_no: '',
    vat: '',
    status: true,
});

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
const message = useMessage();
const loading = ref(false);

const submit = () => {
    formRef.value?.validate((errors: any) => {
        if (!errors) {
            loading.value = true;
            router.post('/companies', form.value, {
                onSuccess: () => {
                    message.success('Company created successfully');
                    emit('close');
                },
                onError: () => {
                    message.error('Failed to create company');
                },
                onFinish: () => {
                    loading.value = false;
                },
            });
        }
    });
};

</script>
