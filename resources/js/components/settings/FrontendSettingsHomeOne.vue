<template>
    <n-card>
        <n-form label-placement="top" :model="localForm">
            <!-- Hero Section -->
            <n-divider><h2 class="text-2xl text-green-400">Hero Section</h2></n-divider>
            <n-grid x-gap="12" :cols="3">
                <n-gi>
                    <n-form-item label="Main Heading">
                        <n-input v-model:value="localForm.heroSection.mainHeading" placeholder="Enter main heading" />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Second Heading">
                        <n-input v-model:value="localForm.heroSection.secondHeading" placeholder="Enter second heading" />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Button Text">
                        <n-input v-model:value="localForm.heroSection.buttonText" placeholder="Enter button text" />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Hero Image">
                        <n-upload :custom-request="handleHeroImageUpload" :max="1" :show-file-list="false" accept="image/*">
                            <n-button>Upload Hero Image</n-button>
                        </n-upload>
                        <div v-if="localForm.heroSection.image" class="mt-2">
                            <img :src="localForm.heroSection.image" alt="Hero" class="max-h-300 rounded shadow" />
                        </div>
                    </n-form-item>
                </n-gi>
            </n-grid>
            <!-- Banner Section -->
            <n-divider><h2 class="text-2xl text-green-400">Banner Section</h2></n-divider>
            <n-grid x-gap="12" :cols="3">
                <n-gi>
                    <n-form-item label="Main Heading">
                        <n-input v-model:value="localForm.bannerSection.mainHeading" placeholder="Enter main heading" />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Second Heading">
                        <n-input v-model:value="localForm.bannerSection.secondHeading" placeholder="Enter second heading" />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Button Text">
                        <n-input v-model:value="localForm.bannerSection.buttonText" placeholder="Enter button text" />
                    </n-form-item>
                </n-gi>
            </n-grid>
            <!-- FAQ Section -->
            <n-divider><h2 class="text-2xl text-green-400">FAQ Section</h2></n-divider>
            <n-form-item label="Section Title">
                <n-input v-model:value="localForm.faqSection.title" placeholder="Enter FAQ title" />
            </n-form-item>
            <n-dynamic-input
                v-model:value="localForm.faqSection.items"
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
        </n-form>
    </n-card>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';

// Type for HomeOne section, feel free to extend for stricter typing
interface HeroSection {
    mainHeading: string;
    secondHeading: string;
    buttonText: string;
    image?: string;
}
interface BannerSection {
    mainHeading: string;
    secondHeading: string;
    buttonText: string;
}
interface FaqSection {
    title: string;
    items: { question: string; answer: string }[];
}
interface HomeOneForm {
    heroSection: HeroSection;
    bannerSection: BannerSection;
    faqSection: FaqSection;
}

const props = defineProps<{
    modelValue: HomeOneForm,
    handleHeroImageUpload: (options: { file: any; onSuccess: () => void; onError: () => void }) => Promise<void>;
}>();
const emit = defineEmits(['update:modelValue']);

const localForm = ref(JSON.parse(JSON.stringify(props.modelValue)));

watch(localForm, (val) => {
    emit('update:modelValue', val);
}, { deep: true });

const handleHeroImageUpload = async (options: any) => {
    if (props.handleHeroImageUpload) {
        await props.handleHeroImageUpload(options);
        // You may want to update the image property directly if needed,
        // for example, if your handler returns a new image URL.
        // localForm.value.heroSection.image = options?.file?.url || localForm.value.heroSection.image;
    }
};
</script>
