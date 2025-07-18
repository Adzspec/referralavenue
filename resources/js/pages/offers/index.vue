<template>
    <Head title="Offers" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-semibold">Offers</h1>
                <n-button type="primary" @click="openCreateModal">Add Offer</n-button>
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
                :data="props.offers.data"
                :pagination="false"
                :row-key="row => row.id"
                checkable
                v-model:checked-row-keys="selectedRowKeys"
                class="rounded shadow dark:bg-gray-900"
            />

            <div class="mt-4 flex justify-end">
                <n-pagination
                    :page="props.offers.current_page"
                    :page-count="props.offers.last_page"
                    @update:page="(page) => $inertia.get('/offers', { page }, { preserveScroll: true })"
                />
            </div>
            <!-- Create Modal -->
            <CreateOfferModal
                v-if="showCreateModal"
                :show="showCreateModal"
                @close="closeModals"
                @submit="handleCreateOffer"
            />

            <!-- Edit Modal -->
            <!--                v-if="showEditModal"-->
            <!--                :show="showEditModal"-->
            <!--                :offer="selectedOffer"-->
            <!--                @close="closeModals"-->
            <!--                @submit="handleUpdateOffer"-->
            <!--            /></>-->
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, h } from 'vue';
import { NButton, NDataTable, NPagination, useMessage } from 'naive-ui';
import type { BreadcrumbItemType } from '@/types';
import CreateOfferModal from '@/components/offers/CreateOfferModal.vue';
// import EditOfferModal from '@/components/offers/EditOfferModal.vue';
import { useCrud } from '@/composables/useCrud';
import BulkActions from '@/components/common/BulkActions.vue';

interface Offer {
    id: number;
    title: string;
    description: string | null;
    code: string | null;
    start_date: string | null;
    end_date: string | null;
    is_featured: boolean;
    is_exclusive: boolean;
    is_deal: boolean;
    status: boolean;
    store_id: number;
    store?: {
        name: string;
    };
}

const props = defineProps<{
    offers: {
        data: Array<any>;
        current_page: number;
        last_page: number;
    };
    can: { create: boolean; edit: boolean; delete: boolean };
}>();

const breadcrumbs: BreadcrumbItemType[] = [
    { title: 'Offers', href: '/offers' },
];

const showCreateModal = ref(false);
const showEditModal = ref(false);
const selectedOffer = ref<Offer | null>(null);
const message = useMessage();
const selectedRowKeys = ref<number[]>([]);
const { create, destroy } = useCrud({ baseUrl: '/offers' });

const openCreateModal = () => (showCreateModal.value = true);
const openEditModal = (offer: Offer) => {
    selectedOffer.value = offer;
    showEditModal.value = true;
};
const closeModals = () => {
    showCreateModal.value = false;
    showEditModal.value = false;
    selectedOffer.value = null;
};

const handleCreateOffer = (data: any) => {
    create(data, {
        onSuccess: () => {
            message.success('Offer created successfully');
            closeModals();
            router.reload();
        },
        onError: () => message.error('Failed to create offer'),
    });
};

// const handleUpdateOffer = (data: any) => {
//     if (!selectedOffer.value) return;
//     update(selectedOffer.value.id, data, {
//         onSuccess: () => {
//             message.success('Offer updated successfully');
//             closeModals();
//             router.reload();
//         },
//         onError: () => message.error('Failed to update offer'),
//     });
// };

const handleDelete = (id: number) => {
    destroy(id, 'Delete Offer', 'Are you sure you want to delete this offer?');
};

const columns = [
    {
        type: 'selection' as const,
    },
    {
        title: 'Image',
        key: 'image',
        render(row:any) {
            return h('img', {
                src: row.thumbnail || '/placeholder.png', // fallback if image is null
                style: {
                    width: '120px',
                    height: '90px',
                    objectFit: 'cover',
                    borderRadius: '6px',
                },
            });
        },
    },
    {
        title: 'Title',
        key: 'title',
    },
    {
        title: 'Store',
        key: 'store',
        render(row: Offer) {
            return row.store?.name || 'N/A';
        }
    },
    {
        title: 'Type',
        key: 'type',
        render(row: Offer) {
            if (row.is_deal) return 'Deal';
            if (row.code) return 'Coupon';
            return 'Offer';
        }
    },
    {
        title: 'Status',
        key: 'status',
        render(row: Offer) {
            return row.status ? 'Active' : 'Inactive';
        }
    },
    {
        title: 'Featured',
        key: 'is_featured',
        render(row: Offer) {
            return row.is_featured ? 'Yes' : 'No';
        }
    },
    {
        title: 'Exclusive',
        key: 'is_exclusive',
        render(row: Offer) {
            return row.is_exclusive ? 'Yes' : 'No';
        }
    },
    {
        title: 'Actions',
        key: 'actions',
        render(row: Offer) {
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
const bulkDelete = (ids: (string | number)[]) => {
    router.post('/offers/bulk-delete', { ids }, {
        onSuccess: () => {
            message.success('Deleted');
            selectedRowKeys.value = [];
            router.reload();
        }
    });
};

const bulkChangeStatus = (ids: (string | number)[], status: number) => {
    router.post('/offers/bulk-status', { ids, status }, {
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
