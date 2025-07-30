<template>
    <n-card>
        <n-form label-placement="top" :model="localForm">
            <n-grid x-gap="12" :cols="3">
                <n-gi>
                    <n-form-item label="Primary Color" path="colors.primaryColor">
                        <n-color-picker
                            v-model:value="localForm.colors.primaryColor"
                            :show-alpha="false"
                            :modes="['hex']"
                            :swatches="['#FFFFFF', '#18A058', '#2080F0', '#F0A020']"
                        />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Secondary Color" path="colors.secondaryColor">
                        <n-color-picker
                            v-model:value="localForm.colors.secondaryColor"
                            :show-alpha="false"
                            :modes="['hex']"
                            :swatches="['#FFFFFF', '#18A058', '#2080F0', '#F0A020']"
                        />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Company Logo" path="logo">
                        <n-upload :custom-request="handleLogoUpload" :max="1" :show-file-list="false" accept="image/*">
                            <n-button>Upload Logo</n-button>
                        </n-upload>
                        <div v-if="localForm.logo" class="mt-2">
                            <img :src="localForm.logo" alt="Logo" class="max-h-16 rounded shadow" />
                        </div>
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
        colors: {
            primaryColor: string;
            secondaryColor: string;
        };
        logo: string;
    };
    handleLogoUpload: (options: { file: any; onSuccess: () => void; onError: () => void }) => Promise<void>;
}>();

const emits = defineEmits(['update:modelValue']);
const localForm = ref(JSON.parse(JSON.stringify(props.modelValue)));
watch(localForm, (val) => {
    emits('update:modelValue', val);
}, { deep: true });

const handleLogoUpload = async (args: any) => {
    if (props.handleLogoUpload) {
        await props.handleLogoUpload(args);
        localForm.value.logo = args?.file?.url || localForm.value.logo;
    }
};
</script>
