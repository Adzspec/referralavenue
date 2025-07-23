<template>
    <Head title="Company Subscriptions" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-semibold">Company Subscriptions</h1>
                <n-button @click="subscribe('price_1RnwDhCTIpgMLNY3CWmdMBTT')" :loading="loading">
                    Subscribe
                </n-button>
                <n-button v-if="props.can.create" type="primary" @click="openCreateModal">Add Subscription</n-button>
            </div>

            <n-data-table
                :columns="columns"
                :data="props.companySubscriptions.data"
                :pagination="false"
                class="rounded shadow dark:bg-gray-900"
            />

            <div class="mt-6 flex justify-end">
                <n-pagination
                    :page="props.companySubscriptions.current_page"
                    :page-count="props.companySubscriptions.last_page"
                    @update:page="page => $inertia.get('/company_subscriptions', { page })"
                />
            </div>

            <!-- Create Modal -->
            <CreateCompanySubscriptionModal
                v-if="showCreateModal"
                :show="showCreateModal"
                :companies="props.companies"
                :subscriptions="props.subscriptions"
                @close="closeModals"
            />

            <!-- Edit Modal -->
            <EditCompanySubscriptionModal
                v-if="showEditModal"
                :show="showEditModal"
                :companies="props.companies"
                :subscriptions="props.subscriptions"
                :subscription-record="selectedSubscription"
                @close="closeModals"
            />
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, h, onMounted } from 'vue';
import {
    NButton,
    NDataTable,
    NPagination,
    useDialog,
    useMessage,
} from 'naive-ui';
import type { BreadcrumbItemType } from '@/types';
import CreateCompanySubscriptionModal from '@/components/company-subscriptions/CreateCompanySubscriptionModal.vue';
import EditCompanySubscriptionModal from '@/components/company-subscriptions/EditCompanySubscriptionModal.vue';

const props = defineProps<{
    companySubscriptions: {
        data: Array<any>;
        current_page: number;
        last_page: number;
    };
    companies: Array<{ id: number; name: string }>;
    subscriptions: Array<{ id: number; name: string; price: string }>;
    can: { create: boolean; edit: boolean; delete: boolean };
}>();

const dialog = useDialog();
const message = useMessage();

const showCreateModal = ref(false);
const showEditModal = ref(false);
const selectedSubscription = ref<any>(null);

const openCreateModal = () => (showCreateModal.value = true);
const openEditModal = (subscription: any) => {
    selectedSubscription.value = subscription;
    showEditModal.value = true;
};
const closeModals = () => {
    showCreateModal.value = false;
    showEditModal.value = false;
    selectedSubscription.value = null;
};
const loading = ref(false);

const subscribe = async (priceId:any) => {
    loading.value = true;
    try {
        router.post('/subscriptions/checkout', {price:priceId}, {
            onSuccess: (res) => {
                console.log(res)
            },
            onFinish: () => loading.value = false
        })

    } finally {
        loading.value = false;
    }
};
const handleDelete = (id: number) => {
    dialog.warning({
        title: 'Delete Subscription',
        content: 'Are you sure you want to delete this record?',
        positiveText: 'Yes',
        negativeText: 'Cancel',
        onPositiveClick: () => {
            router.delete(`/company_subscriptions/${id}`, {
                onSuccess: () => {
                    message.success('Deleted successfully');
                    router.reload();
                },
                onError: () => {
                    message.error('Failed to delete');
                }
            });
        },
    });
};

const columns = [
    {
        title: 'Company',
        key: 'company',
        render: (row: any) => row.company?.name || '—',
    },
    {
        title: 'Plan',
        key: 'subscription',
        render: (row: any) => row.subscription?.name || '—',
    },
    {
        title: 'Start Date',
        key: 'start_date',
    },
    {
        title: 'End Date',
        key: 'end_date',
    },
    {
        title: 'Status',
        key: 'status',
        render: (row: any) => row.status.charAt(0).toUpperCase() + row.status.slice(1),
    },
    {
        title: 'Actions',
        key: 'actions',
        render(row: any) {
            return h('div', { class: 'flex gap-2' }, [
                props.can.edit &&
                h(
                    NButton,
                    {
                        type: 'primary',
                        size: 'small',
                        onClick: () => openEditModal(row),
                    },
                    { default: () => 'Edit' }
                ),
                props.can.delete &&
                h(
                    NButton,
                    {
                        type: 'error',
                        size: 'small',
                        onClick: () => handleDelete(row.id),
                    },
                    { default: () => 'Delete' }
                ),
            ]);
        },
    },
];

const breadcrumbs: BreadcrumbItemType[] = [
    { title: 'Company Subscriptions', href: '/company_subscriptions' },
];
const page = usePage();

// const subscriptionWarning = page.props.subscription_warning as string | null;

onMounted(() => {
    const subscriptionWarning = page.props.subscription_warning as string | null;
    if (subscriptionWarning) {
        message.error(subscriptionWarning);
    }
});

</script>
