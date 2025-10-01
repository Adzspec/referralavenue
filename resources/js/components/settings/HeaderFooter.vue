<template>
    <n-card>
        <n-form label-placement="top" :model="localForm">
            <!-- Header -->
            <n-divider><h2 class="text-2xl text-blue-400">Header</h2></n-divider>
            <n-grid x-gap="12" :cols="2">
                <n-gi>
                    <n-form-item label="Home Text">
                        <n-input v-model:value="localForm.headerFooter.header.home.text" placeholder="Enter Home Text" />
                    </n-form-item>
                </n-gi>

                <n-gi>
                    <n-form-item label="Categories Text">
                        <n-input v-model:value="localForm.headerFooter.header.categories.text" placeholder="Enter Categories Text" />
                    </n-form-item>
                </n-gi>

                <n-gi>
                    <n-form-item label="Browse Text">
                        <n-input v-model:value="localForm.headerFooter.header.browse.text" placeholder="Enter Browse Text" />
                    </n-form-item>
                </n-gi>

                <n-gi>
                    <n-form-item label="Contact Text">
                        <n-input v-model:value="localForm.headerFooter.header.contact.text" placeholder="Enter Contact Text" />
                    </n-form-item>
                </n-gi>
            </n-grid>

            <!-- Footer -->
            <n-divider><h2 class="text-2xl text-blue-400">Footer</h2></n-divider>
            <n-divider><h2 class="text-2xl text-blue-400">Useful Links</h2></n-divider>
            <n-grid x-gap="12" :cols="1">
                <n-gi>
                    <n-form-item label="Useful Links Main Heading">
                        <n-input v-model:value="localForm.headerFooter.footer.usefulLinks.mainHeading" placeholder="Enter Main Heading" />
                    </n-form-item>
                </n-gi>
            </n-grid>
            <n-grid x-gap="12" :cols="2">
                <n-gi>
                    <n-form-item label="Categories Text">
                        <n-input v-model:value="localForm.headerFooter.footer.usefulLinks.categories.text" placeholder="Enter Categories Text" />
                    </n-form-item>
                </n-gi>

                <n-gi>
                    <n-form-item label="Browse Text">
                        <n-input v-model:value="localForm.headerFooter.footer.usefulLinks.discounts.text" placeholder="Enter Browse Text" />
                    </n-form-item>
                </n-gi>

                <n-gi>
                    <n-form-item label="Contact Text">
                        <n-input v-model:value="localForm.headerFooter.header.contact.text" placeholder="Enter Contact Text" />
                    </n-form-item>
                </n-gi>
            </n-grid>
            <n-divider><h2 class="text-2xl text-blue-400">Business Links</h2></n-divider>
            <n-grid x-gap="12" :cols="1">
                <n-gi>
                    <n-form-item label="Useful Links Main Heading">
                        <n-input v-model:value="localForm.headerFooter.footer.businessLinks.mainHeading" placeholder="Enter Main Heading" />
                    </n-form-item>
                </n-gi>
            </n-grid>
            <n-grid x-gap="12" :cols="2">
                <n-gi>
                    <n-form-item label="Categories Text">
                        <n-input v-model:value="localForm.headerFooter.footer.businessLinks.termOfService.text" placeholder="Enter Categories Text" />
                    </n-form-item>
                </n-gi>

                <n-gi>
                    <n-form-item label="Browse Text">
                        <n-input v-model:value="localForm.headerFooter.footer.businessLinks.privacyPolicy.text" placeholder="Enter Browse Text" />
                    </n-form-item>
                </n-gi>

                <n-gi>
                    <n-form-item label="Contact Text">
                        <n-input v-model:value="localForm.headerFooter.footer.businessLinks.cookiePolicy.text" placeholder="Enter Contact Text" />
                    </n-form-item>
                </n-gi>
            </n-grid>
            <n-divider><h2 class="text-2xl text-blue-400">News Letter</h2></n-divider>
            <n-grid x-gap="12" :cols="1">
                <n-gi>
                    <n-form-item label="Useful Links Main Heading">
                        <n-input v-model:value="localForm.headerFooter.footer.newsLetter.mainHeading" placeholder="Enter Main Heading" />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Useful Links Main Heading">
                        <n-input v-model:value="localForm.headerFooter.footer.newsLetter.subHeading" placeholder="Enter Main Heading" />
                    </n-form-item>
                </n-gi>
            </n-grid>
        </n-form>
    </n-card>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'

const props = defineProps<{
    modelValue?: {
        headerFooter: {
            header: {
                home: { text: string; link: string }
                categories: { text: string; link: string }
                browse: { text: string; link: string }
                contact: { text: string; link: string }
            }
            footer: {
                usefulLinks: { mainHeading: string },
                businessLinks: { mainHeading: string },
                newsLetter: { mainHeading: string, subHeading: string },
            }
        }
    }
}>()
const emits = defineEmits(['update:modelValue'])

// ✅ Default safe structure
const defaultValue = {
    headerFooter: {
        header: {
            home: { text: '', link: '' },
            categories: { text: '', link: '' },
            browse: { text: '', link: '' },
            contact: { text: '', link: '' }
        },
        footer: {
            usefulLinks: { mainHeading: '',
                categories: { text: '', link: '' },
                discounts: { text: '', link: '' },
                contact: { text: '', link: '' }
            },
            businessLinks: { mainHeading: '',
                termOfService: { text: '', link: '' },
                privacyPolicy: { text: '', link: '' },
                cookiePolicy: { text: '', link: '' }
            },
            newsLetter: { mainHeading: '',subHeading: '' }
        }
    }
}

// ✅ Always merge props with default
const localForm = ref(Object.assign({}, defaultValue, props.modelValue))

watch(localForm, (val) => {
    emits('update:modelValue', val)
}, { deep: true })
</script>
