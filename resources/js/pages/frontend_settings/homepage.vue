<template>
    <Head title="Frontend Settings" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="mb-4 flex items-center justify-between">
                <h1 class="text-2xl font-semibold">Frontend Settings</h1>
            </div>

            <n-tabs type="card" animated placement="left">
                <n-tab-pane name="homePage" tab="Home Page">
                    <n-card>
                        <n-form label-placement="top" :model="form">
                            <n-grid x-gap="12" :cols="3">
                                <n-gi>
                                    <n-form-item label="Select Home Page" path="form.home_page">
                                        <n-select v-model:value="form.homePage" :options="homePageOptions" placeholder="Choose Home Page" />
                                    </n-form-item>
                                </n-gi>
                                <n-gi>
                                    <n-form-item label="Primary Color" path="color">
                                        <n-color-picker
                                            v-model:value="form.primaryColor"
                                            :show-alpha="false"
                                            :modes="['hex']"
                                            :swatches="['#FFFFFF', '#18A058', '#2080F0', '#F0A020']"
                                        />
                                    </n-form-item>
                                </n-gi>
                                <n-gi>
                                    <n-form-item label="Company Logo" path="form.logo">
                                        <n-upload :custom-request="handleLogoUpload" :max="1" :show-file-list="false" accept="image/*">
                                            <n-button>Upload Logo</n-button>
                                        </n-upload>
                                        <div v-if="form.logo" class="mt-2">
                                            <img :src="form.logo" alt="Logo" class="max-h-16 rounded shadow" />
                                        </div>
                                    </n-form-item>
                                </n-gi>
                            </n-grid>
                            <n-space justify="end" class="mt-4">
                                <n-button type="primary" native-type="submit" :loading="saving" @click="saveSettings">Save</n-button>
                            </n-space>
                        </n-form>
                    </n-card>
                </n-tab-pane>
            </n-tabs>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { useMessage } from 'naive-ui';
import { ref } from 'vue';

import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItemType } from '@/types';

import { useCrud } from '@/composables/useCrud';

const props = defineProps<{
    settings: Record<string, any>;
    can?: { edit?: boolean };
}>();

const homePageOptions = [
    { label: 'Home One', value: 'homeOne' },
    { label: 'Home Two', value: 'homeTwo' },
    { label: 'Home Three', value: 'homeThree' },
];

const breadcrumbs: BreadcrumbItemType[] = [{ title: 'Frontend Settings', href: '/company/settings' }];
const { upload } = useCrud({ baseUrl: '/company/settings' });
const message = useMessage();
const saving = ref(false);
// Make a reactive copy of settings (never mutate props directly!)
const form = ref({ ...props.settings });

const saveSettings = async () => {
    saving.value = true;
    try {
        // Use Inertia if you want a seamless update:
        router.put(
            '/company/settings',
            { settings: form.value },
            {
                onSuccess: () => message.success('Settings saved!'),
                onError: () => message.error('Could not save settings'),
            },
        );
    } finally {
        saving.value = false;
    }
};

const handleLogoUpload = async ({ file, onSuccess, onError }: any) => {
    try {
        // Define expected response type for clarity
        type UploadResponse = { logo_url: string };
        const result = await upload<UploadResponse>('/fileupload', file.file, 'uploadedfile');
        form.value.logo = result.logo_url;
        onSuccess();
    } catch {
        onError();
    }
};
</script>
