<template>
    <Head title="Subscriptions" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-semibold">Subscriptions</h1>
                <n-button v-if="props.can.create" type="primary" @click="openCreateModal">Add Subscription</n-button>
            </div>

            <n-data-table
                :columns="columns"
                :data="tableData"
                :pagination="false"
                class="rounded shadow"
                size="small"
            />

            <div class="mt-4 flex justify-end">
<!--                <n-pagination-->
<!--                    :page="props.subscriptions.current_page"-->
<!--                    :page-count="props.subscriptions.last_page"-->
<!--                    @update:page="(page) => $inertia.get('/subscriptions', { page }, { preserveScroll: true })"-->
<!--                />-->
            </div>
        </div>

        <CreateSubscriptionModal
            v-if="showCreateModal"
            :show="showCreateModal"
            @close="closeModals"
        />

        <EditSubscriptionModal
            v-if="showEditModal"
            :show="showEditModal"
            :subscription="selectedSubscription"
            @close="closeModals"
        />
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref, h, computed } from 'vue';
import {
    NButton,
    NDataTable,
    useDialog,
    useMessage,
} from 'naive-ui';

import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItemType } from '@/types';

import CreateSubscriptionModal from '@/components/subscriptions/CreateSubscriptionModal.vue';
import EditSubscriptionModal from '@/components/subscriptions/EditSubscriptionModal.vue';

const props = defineProps<{
    subscriptions: {
        data: Array<any>;
        current_page: number;
        last_page: number;
    };
    can: { create: boolean; edit: boolean; delete: boolean };
}>();
const tableData = computed(() => props.subscriptions?.data ?? [])
const breadcrumbs: BreadcrumbItemType[] = [
    { title: 'Subscriptions', href: '/subscriptions' }
];

const showCreateModal = ref(false);
const showEditModal = ref(false);
const selectedSubscription = ref<any | null>(null);

const openCreateModal = () => showCreateModal.value = true;

const openEditModal = (subscription: any) => {
    selectedSubscription.value = subscription;
    showEditModal.value = true;
};

const closeModals = () => {
    showCreateModal.value = false;
    showEditModal.value = false;
    selectedSubscription.value = null;
};

const dialog = useDialog();
const message = useMessage();

const confirmDelete = (id: number) => {
    dialog.warning({
        title: 'Delete Subscription',
        content: 'Are you sure you want to delete this subscription?',
        positiveText: 'Yes',
        negativeText: 'Cancel',
        onPositiveClick: () => {
            router.delete(`/subscriptions/${id}`, {
                onSuccess: () => {
                    message.success('Deleted successfully');
                    router.reload();
                },
                onError: () => {
                    message.error('Failed to delete');
                }
            });
        }
    });
};

const columns = [
    { title: 'Name', key: 'name' },
    { title: 'Price', key: 'price' },
    { title: 'Duration', key: 'duration' },
    {
        title: 'Status',
        key: 'status',
        render: (row: any) => row.status ? 'Active' : 'Inactive'
    },
    {
        title: 'Actions',
        key: 'actions',
        render(row: any) {
            return h('div', { class: 'flex gap-2' }, [
                props.can.edit && h(NButton, {
                    size: 'small',
                    type: 'primary',
                    onClick: () => openEditModal(row),
                }, { default: () => 'Edit' }),
                props.can.edit && h(NButton, {
                    size: 'small',
                    type: 'error',
                    onClick: () => confirmDelete(row.id),
                }, { default: () => 'Delete' }),
            ]);
        }
    }
];
</script>
