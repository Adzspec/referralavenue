<template>
    <Head title="Frontend Settings" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="mb-4 flex items-center justify-between">
                <h1 class="text-2xl font-semibold">Frontend Settings</h1>
            </div>

            <n-tabs type="card" animated placement="left">
                <n-tab-pane name="colors" tab="Colors & Logo">
                    <FrontendSettingsColors
                        v-model="form"
                        :handleLogoUpload="handleLogoUpload"
                    />
                    <n-space justify="end" class="mt-4">
                        <n-button type="primary" native-type="submit" :loading="saving" @click="saveSettings">Save</n-button>
                    </n-space>
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
                                <FrontendSettingsHomeOne
                                    v-if="form.homePage === 'homeOne'"
                                    v-model="form.homeOne"
                                    :handle-hero-image-upload="handleHeroImageUpload"
                                />
                            </div>
                            <div v-if="form.homePage === 'homeTwo'" class="mt-6">
                                <FrontendSettingsHomeTwo
                                    v-if="form.homePage === 'homeTwo'"
                                    v-model="form.homeTwo"
                                />
                            </div>
                            <div v-if="form.homePage === 'homeThree'" class="mt-6">
                                <FrontendSettingsHomeThree
                                    v-if="form.homePage === 'homeThree'"
                                    v-model="form.homeThree"
                                    :handle-slider-image-upload="handleSliderImageUpload"
                                />
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
import FrontendSettingsColors from '@/components/settings/SettingsTabColors.vue'
import FrontendSettingsHomeOne from '@/components/settings/FrontendSettingsHomeOne.vue';
import FrontendSettingsHomeTwo from '@/components/settings/FrontendSettingsHomeTwo.vue';
import FrontendSettingsHomeThree from '@/components/settings/FrontendSettingsHomeThree.vue';


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
function getDefaultHomeTwo(settings: any) {
    return {
        heroSection: {
            mainHeading: settings?.homeTwo?.heroSection?.mainHeading || '',
            secondHeading: settings?.homeTwo?.heroSection?.secondHeading || '',
            // buttonText: settings?.homeOne?.heroSection?.buttonText || '',
            // image: settings?.homeOne?.heroSection?.image || ''
        },
        bannerSection: {
            mainHeading: settings?.homeTwo?.bannerSection?.mainHeading || '',
            secondHeading: settings?.homeTwo?.bannerSection?.secondHeading || '',
            buttonText: settings?.homeTwo?.bannerSection?.buttonText || '',
            // image: settings?.homeOne?.bannerSection?.image || ''
        },
        faqSection: {
            title: props.settings?.homeTwo?.faqSection?.title || '',
            items: props.settings?.homeTwo?.faqSection?.items || [
                { question: '', answer: '' }
            ]
        }
    };
}

function getDefaultHomeThree(settings: any) {
    return {
        heroSection: {
            mainHeading: settings?.homeThree?.heroSection?.mainHeading || '',
            secondHeading: settings?.homeThree?.heroSection?.secondHeading || '',
        },
        bannerSection: {
            mainHeading: settings?.homeThree?.bannerSection?.mainHeading || '',
            secondHeading: settings?.homeThree?.bannerSection?.secondHeading || '',
            buttonText: settings?.homeThree?.bannerSection?.buttonText || '',
        },
        faqSection: {
            title: props.settings?.homeThree?.faqSection?.title || '',
            items: props.settings?.homeThree?.faqSection?.items || [
                { question: '', answer: '' }
            ]
        },
        sliderImages: settings?.homeThree?.sliderImages || []
    };
}

function getDefaultColors(settings: any) {
    return {
        primaryColor: settings?.colors?.primaryColor || '#c5497d',
        secondaryColor: settings?.colors?.secondaryColor || '#a76e81',
    };
}

const form = ref({
    homePage: props.settings.homePage || 'homeOne',
    homeOne: getDefaultHomeOne(props.settings),
    homeTwo: getDefaultHomeTwo(props.settings),
    homeThree: getDefaultHomeThree(props.settings),
    primaryColor: props.settings.primaryColor || '#18A058',
    logo: props.settings.logo || '',
    colors: getDefaultColors(props.settings),
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

const handleSliderImageUpload = async ({ file, onError }: any) => {
    try {
        const result = await upload<{ logo_url: string }>('/fileupload', file.file, 'uploadedfile');
        // onSuccess();
        return result.logo_url;
    } catch {
        onError();
        return '';
    }
};

</script>
