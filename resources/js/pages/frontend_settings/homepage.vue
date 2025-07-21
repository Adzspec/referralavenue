<template>
    <Head title="Frontend Settings" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="mb-4 flex items-center justify-between">
                <h1 class="text-2xl font-semibold">Frontend Settings</h1>
            </div>

            <n-tabs type="card" animated placement="left">
                <n-tab-pane name="colors" tab="Colors & Logo">
                    <n-card>
                        <n-form label-placement="top" :model="form">
                            <n-grid x-gap="12" :cols="3">
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
                <n-tab-pane name="homePage" tab="Home Page">
                    <n-card>
                        <n-form label-placement="top" :model="form">
                            <n-grid x-gap="12" :cols="3">
                                <n-gi>
                                    <n-form-item label="Select Home Page" path="form.home_page">
                                        <n-select v-model:value="form.homePage" :options="homePageOptions" placeholder="Choose Home Page" />
                                    </n-form-item>
                                </n-gi>
                            </n-grid>
                            <div v-if="form.homePage === 'homeOne'" class="mt-6">
                                <n-divider><h2 class="text-2xl text-green-400">Hero Section (Home One)</h2></n-divider>
                                <n-grid x-gap="12" :cols="3">
                                    <n-gi>
                                        <n-form-item label="Main Heading">
                                            <n-input v-model:value="form.homeOne.heroSection.mainHeading" placeholder="Enter main heading" />
                                        </n-form-item>
                                    </n-gi>
                                    <n-gi>
                                        <n-form-item label="Second Heading">
                                            <n-input v-model:value="form.homeOne.heroSection.secondHeading" placeholder="Enter second heading" />
                                        </n-form-item>
                                    </n-gi>
                                    <n-gi>
                                        <n-form-item label="Button Text">
                                            <n-input v-model:value="form.homeOne.heroSection.buttonText" placeholder="Enter button text" />
                                        </n-form-item>
                                    </n-gi>
                                    <n-gi>
                                        <n-form-item label="Hero Image">
                                            <n-upload :custom-request="handleHeroImageUpload" :max="1" :show-file-list="false" accept="image/*">
                                                <n-button>Upload Hero Image</n-button>
                                            </n-upload>
                                            <div v-if="form.homeOne.heroSection.image" class="mt-2">
                                                <img :src="form.homeOne.heroSection.image" alt="Hero" class="max-h-300 rounded shadow" />
                                            </div>
                                        </n-form-item>
                                    </n-gi>
                                </n-grid>
                                <n-divider><h2 class="text-2xl text-green-400">Banner Section (Home One)</h2></n-divider>
                                <n-grid x-gap="12" :cols="3">
                                    <n-gi>
                                        <n-form-item label="Main Heading">
                                            <n-input v-model:value="form.homeOne.bannerSection.mainHeading" placeholder="Enter main heading" />
                                        </n-form-item>
                                    </n-gi>
                                    <n-gi>
                                        <n-form-item label="Second Heading">
                                            <n-input v-model:value="form.homeOne.bannerSection.secondHeading" placeholder="Enter second heading" />
                                        </n-form-item>
                                    </n-gi>
                                    <n-gi>
                                        <n-form-item label="Button Text">
                                            <n-input v-model:value="form.homeOne.bannerSection.buttonText" placeholder="Enter button text" />
                                        </n-form-item>
                                    </n-gi>
<!--                                    <n-gi>-->
<!--                                        <n-form-item label="Hero Image">-->
<!--                                            <n-upload :custom-request="handleHeroImageUpload" :max="1" :show-file-list="false" accept="image/*">-->
<!--                                                <n-button>Upload Hero Image</n-button>-->
<!--                                            </n-upload>-->
<!--                                            <div v-if="form.homeOne.bannerSection.image" class="mt-2">-->
<!--                                                <img :src="form.homeOne.bannerSection.image" alt="Hero" class="max-h-20 rounded shadow" />-->
<!--                                            </div>-->
<!--                                        </n-form-item>-->
<!--                                    </n-gi>-->
                                </n-grid>
                                <n-divider><h2 class="text-2xl text-green-400">FAQ Section (Home One)</h2></n-divider>
                                <n-form-item label="Section Title">
                                    <n-input v-model:value="form.homeOne.faqSection.title" placeholder="Enter FAQ title" />
                                </n-form-item>

                                <n-dynamic-input
                                    v-model:value="form.homeOne.faqSection.items"
                                    :on-create="() => ({ question: '', answer: '' })"
                                    show-remove
                                >
                                    <template #default="{ value, index }">
                                        <n-grid :cols="1" :x-gap="12">
                                            <n-gi>
                                                <n-form-item :label="`Question ${index + 1}`">
                                                    <n-input v-model:value="value.question" placeholder="Enter question" />
                                                </n-form-item>
                                            </n-gi>
                                            <n-gi>
                                                <n-form-item :label="`Answer ${index + 1}`">
                                                    <n-input v-model:value="value.answer" type="textarea" placeholder="Enter answer" />
                                                </n-form-item>
                                            </n-gi>
                                        </n-grid>
                                    </template>
                                </n-dynamic-input>

                            </div>

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
const { upload } = useCrud({ baseUrl: '/fileupload' });
const message = useMessage();
const saving = ref(false);
function getDefaultHomeOne(settings: any) {
    return {
        heroSection: {
            mainHeading: settings?.homeOne?.heroSection?.mainHeading || '',
            secondHeading: settings?.homeOne?.heroSection?.secondHeading || '',
            buttonText: settings?.homeOne?.heroSection?.buttonText || '',
            image: settings?.homeOne?.heroSection?.image || ''
        },
        bannerSection: {
            mainHeading: settings?.homeOne?.bannerSection?.mainHeading || '',
            secondHeading: settings?.homeOne?.bannerSection?.secondHeading || '',
            buttonText: settings?.homeOne?.bannerSection?.buttonText || '',
            // image: settings?.homeOne?.bannerSection?.image || ''
        },
        faqSection: {
            title: props.settings?.homeOne?.faqSection?.title || '',
            items: props.settings?.homeOne?.faqSection?.items || [
                { question: '', answer: '' }
            ]
        }
    };
}

const form = ref({
    homePage: props.settings.homePage || 'homeOne',
    homeOne: getDefaultHomeOne(props.settings),
    primaryColor: props.settings.primaryColor || '#18A058',
    logo: props.settings.logo || '',
});




const saveSettings = async () => {
    saving.value = true;
    try {
        // Use Inertia if you want a seamless update:
        router.put(
            '/company/home_settings',
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

const handleHeroImageUpload = async ({ file, onSuccess, onError }: any) => {
    try {
        const result = await upload<{ logo_url: string }>('/fileupload', file.file, 'uploadedfile');
        form.value.homeOne.heroSection.image = result.logo_url;
        onSuccess();
    } catch {
        onError();
    }
};

</script>
