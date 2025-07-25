<template>
    <Head title="Stores" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-semibold">Stores</h1>
                <n-button type="primary" @click="openCreateModal">Add Store</n-button>
            </div>

<!--            <n-data-table-->
<!--                :columns="columns"-->
<!--                :data="stores"-->
<!--                :pagination="false"-->
<!--                :row-key="row => row.id"-->
<!--                class="rounded shadow dark:bg-gray-900"-->
<!--            />-->
            <n-data-table
                :columns="columns"
                :data="props.stores.data"
                :pagination="false"
                class="rounded shadow"
                size="small"
            />

            <div class="mt-4 flex justify-end">
                <n-pagination
                    :page="props.stores.current_page"
                    :page-count="props.stores.last_page"
                    @update:page="(page) => $inertia.get('/stores', { page }, { preserveScroll: true })"
                />
            </div>
            <!-- Create Modal -->
            <CreateStoreModal
                v-if="showCreateModal"
                :show="showCreateModal"
                @close="closeModals"
                @submit="handleCreateStore"
            />

<!--            &lt;!&ndash; Edit Modal &ndash;&gt;-->
            <EditStoreModal
                v-if="showEditModal"
                :show="showEditModal"
                :store="selectedStore"
                @close="closeModals"
                @submit="handleUpdateStore"
            />
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, h } from 'vue';
import { NButton, NDataTable, NPagination, useMessage } from 'naive-ui';
import type { BreadcrumbItemType } from '@/types';
import CreateStoreModal from '@/components/stores/CreateStoreModal.vue';
import EditStoreModal from '@/components/stores/EditStoreModal.vue';
import { useCrud } from '@/composables/useCrud';

interface Store {
    id: number;
    name: string;
    website: string | null;
    status: number;
    image?: string | null;
}

const props = defineProps<{
    stores: {
        data: Array<any>;
        current_page: number;
        last_page: number;
    };
    can: { create: boolean; edit: boolean; delete: boolean };
}>();

const breadcrumbs: BreadcrumbItemType[] = [
    { title: 'Stores', href: '/stores' },
];

const showCreateModal = ref(false);
const showEditModal = ref(false);
const selectedStore = ref<Store | null>(null);
const message = useMessage();

const { create, update, destroy } = useCrud({ baseUrl: '/stores' });

const openCreateModal = () => (showCreateModal.value = true);
const openEditModal = (store: Store) => {
    selectedStore.value = store;
    showEditModal.value = true;
};
const closeModals = () => {
    showCreateModal.value = false;
    showEditModal.value = false;
    selectedStore.value = null;
};

const handleCreateStore = (data: any) => {
    create(data, {
        onSuccess: () => {
            message.success('Store created successfully');
            closeModals();
            router.reload();
        },
        onError: () => message.error('Failed to create store'),
    });
};

const handleUpdateStore = (data: any) => {
    if (!selectedStore.value) return;
    update(selectedStore.value.id, data, {
        onSuccess: () => {
            message.success('Store updated successfully');
            closeModals();
            router.reload();
        },
        onError: () => message.error('Failed to update store'),
    });
};

const handleDelete = (id: number) => {
    destroy(id, 'Delete Store', 'Are you sure you want to delete this store?');
};

const columns = [
    {
        title: 'Name',
        key: 'name',
    },
    {
        title: 'Status',
        key: 'status',
        render(row: Store) {
            return row.status === 1 ? 'Active' : 'Inactive';
        }
    },
    {
        title: 'Actions',
        key: 'actions',
        render(row: Store) {
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
