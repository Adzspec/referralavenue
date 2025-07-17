<template>
    <Head title="Categories" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-semibold">Categories</h1>
                <n-button type="primary" @click="openCreateModal">Add Category</n-button>
            </div>

            <div class="flex justify-between items-center mb-4">
                <div class="flex gap-2">
                    <n-button type="error" @click="bulkDelete" :disabled="!selectedRowKeys.length">
                        Delete Selected
                    </n-button>
                    <n-button @click="() => bulkChangeStatus(1)" :disabled="!selectedRowKeys.length">
                        Mark Active
                    </n-button>
                    <n-button @click="() => bulkChangeStatus(0)" :disabled="!selectedRowKeys.length">
                        Mark Inactive
                    </n-button>
                </div>
            </div>

            <n-data-table
                :columns="columns"
                :data="categories"
                :pagination="false"
                :row-key="row => row.id"
                checkable
                v-model:checked-row-keys="selectedRowKeys"
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
import { Head, router } from '@inertiajs/vue3';
import { ref, h, computed } from 'vue';
import { NButton, NDataTable, useMessage } from 'naive-ui';
import type { BreadcrumbItemType } from '@/types';
import CreateCategoryModal from '@/components/categories/CreateCategoryModal.vue';
import EditCategoryModal from '@/components/categories/EditCategoryModal.vue';
import { useCrud } from '@/composables/useCrud';

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

const message = useMessage();

const showCreateModal = ref(false);
const showEditModal = ref(false);
const selectedCategory = ref<any | null>(null);
const selectedRowKeys = ref<number[]>([]);

const parentCategories = computed(() =>
    props.categories.map(cat => ({
        id: cat.id,
        name: cat.name,
    }))
);

const { create, update, destroy } = useCrud({ baseUrl: '/categories' });

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

const handleCreateCategory = (data: any) => {
    create(data, {
        onSuccess: () => {
            showCreateModal.value = false;
            router.reload();
        },
        onError: () => message.error('Failed to create category'),
    });
};

const handleUpdateCategory = (data: any) => {
    if (!selectedCategory.value) return;
    update(selectedCategory.value.id, data, {
        onSuccess: () => {
            showEditModal.value = false;
            router.reload();
        },
        onError: () => message.error('Failed to update category'),
    });
};

const handleDelete = (id: number) => {
    destroy(id, 'Delete Category', 'Are you sure you want to delete this category?');
};

const bulkDelete = () => {
    if (!selectedRowKeys.value.length) return;
    if (confirm('Are you sure you want to delete selected categories?')) {
        router.post('/categories/bulk-delete', { ids: selectedRowKeys.value }, {
            onSuccess: () => {
                message.success('Selected categories deleted');
                selectedRowKeys.value = [];
                router.reload();
            },
            onError: () => message.error('Bulk delete failed'),
        });
    }
};

const bulkChangeStatus = (status: number) => {
    if (!selectedRowKeys.value.length) return;
    router.post('/categories/bulk-status', { ids: selectedRowKeys.value, status }, {
        onSuccess: () => {
            message.success('Status updated');
            selectedRowKeys.value = [];
            router.reload();
        },
        onError: () => message.error('Bulk status update failed'),
    });
};

const columns = [
    {
        type: 'selection' as const,
    },
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
        },
    },
];
</script>
