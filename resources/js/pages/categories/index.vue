<template>
    <Head title="Categories" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-semibold">Categories</h1>
                <n-button v-if="props.can.create" type="primary" @click="openCreateModal">Add Category</n-button>
            </div>

            <div class="flex justify-between items-center mb-4">
                <!-- ...table, etc -->
                <BulkActions
                    :selectedKeys="selectedRowKeys"
                    :actions="actions"
                    class="mb-4"
                />
            </div>

            <n-data-table
                :columns="columns"
                :data="props.categories.data"
                :pagination="false"
                :row-key="row => row.id"
                checkable
                v-model:checked-row-keys="selectedRowKeys"
                class="rounded shadow dark:bg-gray-900"
            />
            <div class="mt-4 flex justify-end">
                <n-pagination
                    :page="props.categories.current_page"
                    :page-count="props.categories.last_page"
                    @update:page="(page) => $inertia.get('/categories', { page }, { preserveScroll: true })"
                />
            </div>
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
import { NButton, NDataTable, NPagination, useMessage } from 'naive-ui';
import type { BreadcrumbItemType } from '@/types';
import CreateCategoryModal from '@/components/categories/CreateCategoryModal.vue';
import EditCategoryModal from '@/components/categories/EditCategoryModal.vue';
import { useCrud } from '@/composables/useCrud';
import BulkActions from '@/components/common/BulkActions.vue';

interface CategoryNode {
    id: number;
    name: string;
    status: number;
    level?: number;
    children?: CategoryNode[];
}

// const props = defineProps<{
//     categories: CategoryNode[];
// }>();
const props = defineProps<{
    categories: {
        data: Array<any>;
        current_page: number;
        last_page: number;
    };
    can: { create: boolean; edit: boolean; delete: boolean };
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
    props.categories.data.map(cat => ({
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
        title: 'Status',
        key: 'status',
        render: (row: CategoryNode) => row.status === 1 ? 'Active' : 'Inactive',
    },
    {
        title: 'Actions',
        key: 'actions',
        render(row: CategoryNode) {
            return h('div', { class: 'flex gap-2' }, [
                props.can.edit &&
                h(NButton, {
                    size: 'small',
                    type: 'primary',
                    onClick: () => openEditModal(row),
                }, { default: () => 'Edit' }),
                props.can.delete &&
                h(NButton, {
                    size: 'small',
                    type: 'error',
                    onClick: () => handleDelete(row.id),
                }, { default: () => 'Delete' }),
            ]);
        },
    },
];
const bulkDelete = (ids: (string | number)[]) => {
    router.post('/categories/bulk-delete', { ids }, {
        onSuccess: () => {
            message.success('Deleted');
            selectedRowKeys.value = [];
            router.reload();
        }
    });
};

const bulkChangeStatus = (ids: (string | number)[], status: number) => {
    router.post('/categories/bulk-status', { ids, status }, {
        onSuccess: () => {
            message.success('Status updated');
            selectedRowKeys.value = [];
            router.reload();
        }
    });
};
const actions = [
    {
        label: 'Delete',
        type: 'error',
        confirm: 'Are you sure you want to delete the selected items?',
        handler: bulkDelete
    },
    {
        label: 'Set Active',
        type: 'success',
        confirm: 'Mark selected as Active?',
        handler: (ids: (string | number)[]) => bulkChangeStatus(ids, 1)
    },
    {
        label: 'Set Inactive',
        type: 'warning',
        confirm: 'Mark selected as Inactive?',
        handler: (ids: (string | number)[]) => bulkChangeStatus(ids, 0)
    }
];

</script>
