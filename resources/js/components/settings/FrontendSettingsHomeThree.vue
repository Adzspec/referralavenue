<template>
    <n-card>
        <n-form label-placement="top" :model="localForm">
            <n-divider><h2 class="text-2xl text-green-400">Slider Images</h2></n-divider>
            <n-dynamic-input v-model:value="localForm.sliderImages" :on-create="() => ({ url: '' })" show-remove>
                <template #default="{ value, index }">
                    <n-form-item :label="`Slider Image ${index + 1}`">
                        <n-upload :custom-request="(args) => handleSliderImageUpload(args, index)" :max="1" :show-file-list="false" accept="image/*">
                            <n-button>Upload Image</n-button>
                        </n-upload>
                        <div v-if="value.url" class="mt-2">
                            <img :src="value.url" alt="Slider" class="max-h-24 rounded shadow" />
                        </div>
                    </n-form-item>
                </template>
            </n-dynamic-input>
            <!-- Hero Section -->
<!--            <n-divider><h2 class="text-2xl text-green-400">Hero Section</h2></n-divider>-->
<!--            <n-grid x-gap="12" :cols="3">-->
<!--                <n-gi>-->
<!--                    <n-form-item label="Main Heading">-->
<!--                        <n-input v-model:value="localForm.heroSection.mainHeading" placeholder="Enter main heading" />-->
<!--                    </n-form-item>-->
<!--                </n-gi>-->
<!--                <n-gi>-->
<!--                    <n-form-item label="Second Heading">-->
<!--                        <n-input v-model:value="localForm.heroSection.secondHeading" placeholder="Enter second heading" />-->
<!--                    </n-form-item>-->
<!--                </n-gi>-->
<!--                &lt;!&ndash;-->
<!--                <n-gi>-->
<!--                  <n-form-item label="Button Text">-->
<!--                    <n-input v-model:value="localForm.heroSection.buttonText" placeholder="Enter button text" />-->
<!--                  </n-form-item>-->
<!--                </n-gi>-->
<!--                &ndash;&gt;-->
<!--            </n-grid>-->

            <!-- Banner Section -->
            <n-divider><h2 class="text-2xl text-green-400">Exclusive Section</h2></n-divider>
            <n-grid x-gap="12" :cols="3">
                <n-gi>
                    <n-form-item label="Main Heading">
                        <n-input v-model:value="localForm.exclusiveSection.mainHeading" placeholder="Enter section heading" />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Button Text">
                        <n-input v-model:value="localForm.exclusiveSection.buttonText" placeholder="Enter text fo button" />
                    </n-form-item>
                </n-gi>
            </n-grid>
            <n-divider><h2 class="text-2xl text-green-400">Popular Section</h2></n-divider>
            <n-grid x-gap="12" :cols="3">
                <n-gi>
                    <n-form-item label="Main Heading">
                        <n-input v-model:value="localForm.popularSection.mainHeading" placeholder="Enter section heading" />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Button Text">
                        <n-input v-model:value="localForm.popularSection.buttonText" placeholder="Enter text fo button" />
                    </n-form-item>
                </n-gi>
            </n-grid>
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
            <n-dynamic-input v-model:value="localForm.faqSection.items" :on-create="() => ({ question: '', answer: '' })" show-remove>
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

// Define types for HomeThree
interface HeroSection {
    mainHeading: string;
    secondHeading: string;
}
interface ExclusiveSection {
    mainHeading: string;
    buttonText: string;
}
interface PopularSection {
    mainHeading: string;
    buttonText: string;
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
interface SliderImage {
    url: string;
}
interface HomeThreeForm {
    heroSection: HeroSection;
    bannerSection: BannerSection;
    faqSection: FaqSection;
    sliderImages?: SliderImage[];
    exclusiveSection: ExclusiveSection;
    popularSection: PopularSection;
}

const props = defineProps<{
    modelValue: HomeThreeForm;
    handleSliderImageUpload: (args: { file: any; onSuccess: () => void; onError: () => void }) => Promise<string>;
}>();
const emit = defineEmits(['update:modelValue']);

const localForm = ref(JSON.parse(JSON.stringify(props.modelValue)));

watch(
    localForm,
    (val) => {
        emit('update:modelValue', val);
    },
    { deep: true },
);

const handleSliderImageUpload = async (args: any, index: number) => {
    try {
        // Call parent handler, expect it to return uploaded file URL
        localForm.value.sliderImages[index].url = await props.handleSliderImageUpload(args);
        args.onSuccess();
    } catch {
        args.onError();
    }
};
</script>
