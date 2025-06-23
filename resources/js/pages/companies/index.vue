<template>
    <Head title="Companies" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-semibold">Companies</h1>
                <n-button v-if="props.can.create" type="primary" @click="openCreateModal">Add Company</n-button>
            </div>

            <n-data-table
                :columns="columns"
                :data="props.companies.data"
                :pagination="false"
                class="rounded shadow"
                size="small"
            />

            <div class="mt-4 flex justify-end">
                <n-pagination
                    :page="props.companies.current_page"
                    :page-count="props.companies.last_page"
                    @update:page="(page) => $inertia.get('/companies', { page }, { preserveScroll: true })"
                />
            </div>
        </div>

        <CreateCompanyModal
            v-if="showCreateModal"
            :show="showCreateModal"
            @close="closeModals"
        />

        <EditCompanyModal
            v-if="showEditModal"
            :show="showEditModal"
            :company="selectedCompany"
            @close="closeModals"
        />
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref, h } from 'vue';
import {
    NButton,
    NDataTable,
    NPagination,
    useDialog,
    useMessage,
} from 'naive-ui';

import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItemType } from '@/types';

import CreateCompanyModal from '@/components/companies/CreateCompanyModal.vue';
import EditCompanyModal from '@/components/companies/EditCompanyModal.vue';

const props = defineProps<{
    companies: {
        data: Array<any>;
        current_page: number;
        last_page: number;
    };
    can: { create: boolean; edit: boolean; delete: boolean };
}>();

const breadcrumbs: BreadcrumbItemType[] = [
    { title: 'Companies', href: '/companies' }
];

const showCreateModal = ref(false);
const showEditModal = ref(false);
const selectedCompany = ref<any | null>(null);

const openCreateModal = () => showCreateModal.value = true;

const openEditModal = (company: any) => {
    selectedCompany.value = company;
    showEditModal.value = true;
};

const closeModals = () => {
    showCreateModal.value = false;
    showEditModal.value = false;
    selectedCompany.value = null;
};

const dialog = useDialog();
const message = useMessage();

const confirmDelete = (id: number) => {
    dialog.warning({
        title: 'Delete Company',
        content: 'Are you sure you want to delete this company?',
        positiveText: 'Yes',
        negativeText: 'Cancel',
        onPositiveClick: () => {
            router.delete(`/companies/${id}`, {
                onSuccess: () => {
                    message.success('Deleted successfully');
                    // Option 1: Refetch current page
                    router.reload();

                    // Option 2: Navigate to list explicitly
                    // router.get('/companies');
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
    { title: 'Email', key: 'email' },
    { title: 'Domain', key: 'domain' },
    { title: 'Registration No', key: 'registration_no' },
    { title: 'VAT', key: 'vat' },
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
                h(NButton, {
                    size: 'small',
                    type: 'primary',
                    onClick: () => openEditModal(row),
                }, { default: () => 'Edit' }),
                h(NButton, {
                    size: 'small',
                    type: 'error',
                    onClick: () => confirmDelete(row.id),
                }, { default: () => 'Delete' }),
            ]);
        }
    }
];
</script>
