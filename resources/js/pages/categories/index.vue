<template>
    <Head title="Categories" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-semibold">Categories</h1>
                <n-button type="primary" @click="openCreateModal">Add Category</n-button>
            </div>

            <n-data-table
                :columns="columns"
                :data="categories"
                :pagination="false"
                :row-key="row => row.id"
                class="rounded shadow dark:bg-gray-900"
            />


            <CreateCategoryModal
                v-if="showCreateModal"
                :show="showCreateModal"
                :parent-categories="parentCategories"
                @close="closeModals"
                @submit="handleCreateCategory"
            />

            <EditCategoryModal
                v-if="showEditModal"
                :show="showEditModal"
                :category="selectedCategory"
                :parent-categories="parentCategories"
                @close="closeModals"
                @submit="handleUpdateCategory"
            />

        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, h, computed } from 'vue';
import { NButton, NDataTable } from 'naive-ui';
import type { BreadcrumbItemType } from '@/types';
import CreateCategoryModal from '@/components/categories/CreateCategoryModal.vue';
import EditCategoryModal from '@/components/categories/EditCategoryModal.vue';

interface CategoryNode {
    id: number;
    name: string;
    level?: number;
    children?: CategoryNode[];
}

const props = defineProps<{
    categories: CategoryNode[];
}>();

const breadcrumbs: BreadcrumbItemType[] = [
    { title: 'Categories', href: '/categories' },
];

const showCreateModal = ref(false);
const showEditModal = ref(false);
const selectedCategory = ref<any>(null);

const parentCategories = computed(() =>
    props.categories.map(cat => ({
        id: cat.id,
        name: cat.name,
    }))
);

const openCreateModal = () => (showCreateModal.value = true);

const openEditModal = (category: CategoryNode) => {
    selectedCategory.value = category;
    showEditModal.value = true;
};

const closeModals = () => {
    showCreateModal.value = false;
    showEditModal.value = false;
    selectedCategory.value = null;
};
import { useCrud } from '@/composables/useCrud';

const { create, update, destroy } = useCrud({ baseUrl: '/categories' });

const handleCreateCategory = (data: any) => {
    create(data, {
        onSuccess: () => showCreateModal.value = false,
        onError: () => {}
    });
};

const handleUpdateCategory = (data: any) => {
    if (!selectedCategory.value) return;

    update(selectedCategory.value.id, data, {
        onSuccess: () => showEditModal.value = false,
        onError: () => {}
    });
};

const handleDelete = (id: number) => {
    destroy(id, 'Delete Category', 'Are you sure you want to delete this category?');
};

const columns = [
    {
        title: 'Name',
        key: 'name',
        render: (row: CategoryNode) => `${'—'.repeat(row.level || 0)} ${row.name}`,

    },
    {
        title: 'Actions',
        key: 'actions',
        render(row: CategoryNode) {
            return h('div', { class: 'flex gap-2' }, [
                h(NButton, {
                    size: 'small',
                    type: 'primary',
                    onClick: () => openEditModal(row),
                }, { default: () => 'Edit' }),
                h(NButton, {
                    size: 'small',
                    type: 'error',
                    onClick: () => handleDelete(row.id),
                }, { default: () => 'Delete' }),
            ]);
        }
    }
];
</script>

