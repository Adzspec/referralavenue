<template>
    <n-modal :show="show" title="Create Offer" preset="dialog" @close="emit('close')">
        <n-form
            ref="formRef"
            :model="form"
            :rules="rules"
            label-placement="top"
            class="mt-4"
        >
            <n-form-item label="Title" path="title">
                <n-input v-model:value="form.title" placeholder="Enter offer title" />
            </n-form-item>

            <n-form-item label="Store" path="store_id">
                <n-select v-model:value="form.store_id" :options="storeOptions" placeholder="Select a store" />
            </n-form-item>

            <n-form-item label="Category" path="category_id">
                <n-select v-model:value="form.category_id" :options="categoryOptions" placeholder="Select a category (optional)" clearable />
            </n-form-item>

            <n-form-item label="Description" path="description">
                <n-input type="textarea" v-model:value="form.description" placeholder="Offer description" />
            </n-form-item>

            <n-grid :cols="2" x-gap="12">
                <n-gi>
                    <n-form-item label="Code" path="code">
                        <n-input v-model:value="form.code" placeholder="Coupon code (if applicable)" />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Link" path="link">
                        <n-input v-model:value="form.link" placeholder="Affiliate link" />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Start Date" path="start_date">
                        <n-date-picker v-model:value="form.start_date" type="date" clearable />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="End Date" path="end_date">
                        <n-date-picker v-model:value="form.end_date" type="date" clearable />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Image URL" path="image_url">
                        <n-input v-model:value="form.image_url" placeholder="Image URL" />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Thumbnail" path="thumbnail">
                        <n-input v-model:value="form.thumbnail" placeholder="Thumbnail URL" />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Price" path="price">
                        <n-input-number v-model:value="form.price" placeholder="Price" clearable />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Status" path="status">
                        <n-switch v-model:value="form.status" />
                    </n-form-item>
                </n-gi>
            </n-grid>

            <n-grid :cols="3" x-gap="12">
                <n-gi>
                    <n-form-item label="Featured" path="is_featured">
                        <n-switch v-model:value="form.is_featured" />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Exclusive" path="is_exclusive">
                        <n-switch v-model:value="form.is_exclusive" />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="Deal" path="is_deal">
                        <n-switch v-model:value="form.is_deal" />
                    </n-form-item>
                </n-gi>
            </n-grid>

            <n-collapse>
                <n-collapse-item title="Advanced Options">
                    <n-grid :cols="2" x-gap="12">
                        <n-gi>
                            <n-form-item label="Product URL" path="product_url">
                                <n-input v-model:value="form.product_url" placeholder="Product URL" />
                            </n-form-item>
                        </n-gi>
                        <n-gi>
                            <n-form-item label="Path" path="path">
                                <n-input v-model:value="form.path" placeholder="URL path" />
                            </n-form-item>
                        </n-gi>
                        <n-gi>
                            <n-form-item label="SKU" path="sku">
                                <n-input v-model:value="form.sku" placeholder="SKU" />
                            </n-form-item>
                        </n-gi>
                        <n-gi>
                            <n-form-item label="Product Name" path="product_name">
                                <n-input v-model:value="form.product_name" placeholder="Product name" />
                            </n-form-item>
                        </n-gi>
                        <n-gi>
                            <n-form-item label="Product Price" path="product_price">
                                <n-input-number v-model:value="form.product_price" placeholder="Product price" clearable />
                            </n-form-item>
                        </n-gi>
                        <n-gi>
                            <n-form-item label="Old Price" path="old_price">
                                <n-input-number v-model:value="form.old_price" placeholder="Old price" clearable />
                            </n-form-item>
                        </n-gi>
                        <n-gi>
                            <n-form-item label="Source" path="source">
                                <n-input v-model:value="form.source" placeholder="Source" />
                            </n-form-item>
                        </n-gi>
                        <n-gi>
                            <n-form-item label="External ID" path="external_id">
                                <n-input v-model:value="form.external_id" placeholder="External ID" />
                            </n-form-item>
                        </n-gi>
                    </n-grid>
                </n-collapse-item>
            </n-collapse>
        </n-form>

        <template #action>
            <n-space justify="end">
                <n-button @click="emit('close')">Cancel</n-button>
                <n-button type="primary" @click="submit">Create</n-button>
            </n-space>
        </template>
    </n-modal>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import type { FormInst, FormRules } from 'naive-ui';
import axios from 'axios';

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'submit', payload: Record<string, any>): void;
}>();

// Props are used in the template
const props = defineProps<{
    show: boolean;
}>();

const formRef = ref<FormInst | null>(null);
const storeOptions = ref<{ label: string; value: number }[]>([]);
const categoryOptions = ref<{ label: string; value: number }[]>([]);

const form = ref({
    title: '',
    description: '',
    store_id: null,
    category_id: null,
    product_url: '',
    image_url: '',
    price: null,
    code: '',
    start_date: null,
    end_date: null,
    link: '',
    is_featured: false,
    is_exclusive: false,
    is_deal: false,
    path: '',
    thumbnail: '',
    sku: '',
    product_name: '',
    product_price: null,
    old_price: null,
    source: '',
    external_id: '',
    status: true,
});

const rules: FormRules = {
    title: [{ required: true, message: 'Title is required', trigger: ['blur'] }],
    store_id: [{ required: true, message: 'Store is required', trigger: ['blur', 'change'] }],
};

onMounted(async () => {
    try {
        // Fetch stores for dropdown
        const storesResponse = await axios.get('/api/stores');
        storeOptions.value = storesResponse.data.map((store: any) => ({
            label: store.name,
            value: store.id
        }));

        // Fetch categories for dropdown
        const categoriesResponse = await axios.get('/api/categories');
        categoryOptions.value = categoriesResponse.data.map((category: any) => ({
            label: category.name,
            value: category.id
        }));
    } catch (error) {
        console.error('Failed to fetch dropdown data:', error);
    }
});

const submit = async () => {
    if (!formRef.value) return;
    try {
        await formRef.value.validate();
        emit('submit', form.value);
    } catch (err) {
        // validation failed
    }
};
</script>
