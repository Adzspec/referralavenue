<template>
    <n-card>
        <n-form label-placement="top" :model="localForm">
            <n-grid x-gap="12" :cols="3">
                <n-gi>
                    <n-form-item label="First Phone">
                        <n-input v-model:value="localForm.contactUs.phone1" placeholder="Enter Phone Number" />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Second Phone">
                        <n-input v-model:value="localForm.contactUs.phone2" placeholder="Enter Second Phone Number" />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="First Email">
                        <n-input v-model:value="localForm.contactUs.email1" placeholder="Enter First Email" />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Second Email">
                        <n-input v-model:value="localForm.contactUs.email2" placeholder="Enter Second Email" />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Address">
                        <n-input v-model:value="localForm.contactUs.address" placeholder="Enter Address" />
                    </n-form-item>
                </n-gi>
            </n-grid>
        </n-form>
    </n-card>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
const props = defineProps<{
    modelValue: {
        contactUs: {
            phone1: string;
            phone2: string;
            email1: string;
            email2: string;
            address: string;

        };
    };
}>();

const emits = defineEmits(['update:modelValue']);
const localForm = ref(JSON.parse(JSON.stringify(props.modelValue)));
watch(localForm, (val) => {
    emits('update:modelValue', val);
}, { deep: true });
</script>
