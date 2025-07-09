<template>
    <Head title="Offers" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-semibold">Offers</h1>
                <n-button type="primary" @click="openCreateModal">Add Offer</n-button>
            </div>

            <n-data-table
                :columns="columns"
                :data="props.offers.data"
                :pagination="false"
                class="rounded shadow"
                size="small"
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

const { create, update, destroy } = useCrud({ baseUrl: '/offers' });

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

const handleUpdateOffer = (data: any) => {
    if (!selectedOffer.value) return;
    update(selectedOffer.value.id, data, {
        onSuccess: () => {
            message.success('Offer updated successfully');
            closeModals();
            router.reload();
        },
        onError: () => message.error('Failed to update offer'),
    });
};

const handleDelete = (id: number) => {
    destroy(id, 'Delete Offer', 'Are you sure you want to delete this offer?');
};

const columns = [
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
</script>
