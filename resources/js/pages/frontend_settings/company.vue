<template>
    <Head title="Frontend Settings" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-screen bg-gradient-to-tr from-slate-100 to-blue-50 p-4 md:p-8">
            <div class="mb-6 flex flex-col items-start justify-between gap-2 sm:flex-row sm:items-center">
                <h1 class="text-2xl text-black">Company Settings</h1>
            </div>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                <!-- Company Info Card -->
                <div>
                    <div class="flex h-full flex-col items-center rounded-2xl bg-white/80 p-6 shadow-md transition hover:shadow-lg">
                        <h4 class="mb-3 text-center text-xl font-semibold break-all text-blue-800">
                            {{ company.name }}
                        </h4>
                        <ul class="w-full space-y-2">
                            <InfoTag label="Email" type="success" :value="company.email" />
                            <InfoTag label="Domain" type="info" :value="company.domain" />
                            <InfoTag label="Reg. No" type="warning" :value="company.registration_no" />
                            <InfoTag label="VAT" type="error" :value="company.vat" />
                            <InfoTag label="Status" type="success" :value="company.status ? 'Active' : 'Inactive'" />
                        </ul>
                    </div>
                </div>
                <!-- Profile Card (match look) -->
                <div>
                    <div class="flex h-full flex-col items-center rounded-2xl bg-white/80 p-6 shadow-md transition hover:shadow-lg">
                        <h4 class="mb-3 text-center text-xl font-semibold text-blue-700">Company Profile</h4>
                        <ul class="w-full space-y-2">
                            <li class="flex items-center justify-between text-gray-600">
                                <span class="font-medium">Phone:</span>
                                <span class="truncate text-right">{{ company.profile?.phone || '--' }}</span>
                            </li>
                            <li class="flex items-center justify-between text-gray-600">
                                <span class="font-medium">Address:</span>
                                <span class="truncate text-right">{{ company.profile?.address || '--' }}</span>
                            </li>
                            <li class="flex items-center justify-between text-gray-600">
                                <span class="font-medium">City:</span>
                                <span class="truncate text-right">{{ company.profile?.city || '--' }}</span>
                            </li>
                            <li class="flex items-center justify-between text-gray-600">
                                <span class="font-medium">State:</span>
                                <span class="truncate text-right">{{ company.profile?.state || '--' }}</span>
                            </li>
                            <li class="flex items-center justify-between text-gray-600">
                                <span class="font-medium">Country:</span>
                                <span class="truncate text-right">{{ company.profile?.country || '--' }}</span>
                            </li>
                            <li class="flex items-center justify-between text-gray-600">
                                <span class="font-medium">Zipcode:</span>
                                <span class="truncate text-right">{{ company.profile?.zipcode || '--' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Subscription Card (match look) -->
                <div>
                    <div class="flex h-full flex-col items-center rounded-2xl bg-white/80 p-6 shadow-md transition hover:shadow-lg">
                        <h4 class="mb-3 text-center text-xl font-semibold text-orange-700">Subscription Info</h4>
                        <ul class="w-full space-y-2">
                            <li class="flex items-center justify-between text-gray-600">
                                <span class="font-medium">Current Subscription:</span>
                                <span class="truncate text-right">
                                    <span class="inline-block rounded bg-blue-100 px-2 py-1 text-xs text-blue-700">
                                        {{ company.latest_subscription?.subscription?.name || '—' }}
                                    </span>
                                </span>
                            </li>
                            <li class="flex items-center justify-between text-gray-600">
                                <span class="font-medium">Start Date:</span>
                                <span class="truncate text-right">
                                    <span class="inline-block rounded bg-green-100 px-2 py-1 text-xs text-green-700">
                                        {{ company.latest_subscription?.start_date || '--' }}
                                    </span>
                                </span>
                            </li>
                            <li class="flex items-center justify-between text-gray-600">
                                <span class="font-medium">Expiry Date:</span>
                                <span class="truncate text-right">
                                    <span class="inline-block rounded bg-red-100 px-2 py-1 text-xs text-red-700">
                                        {{ company.latest_subscription?.end_date || '--' }}
                                    </span>
                                </span>
                            </li>
                            <li class="flex items-center justify-between text-gray-600">
                                <span class="font-medium">Status:</span>
                                <span class="truncate text-right">
                                    <span class="inline-block rounded bg-red-100 px-2 py-1 text-xs text-red-700">
                                        {{ company.latest_subscription?.status || '--' }}
                                    </span>
                                </span>
                            </li>
                        </ul>

                        <div class="mt-6 flex w-full justify-end gap-2">
                            <button @click="goHome" class="rounded bg-blue-600 px-4 py-1 text-sm font-medium text-white shadow hover:bg-blue-700">
                                Upgrade Plan
                            </button>
                            <button v-if="company.latest_subscription?.status==='active'" class="rounded bg-red-100 px-4 py-1 text-sm font-medium text-red-600 shadow hover:bg-red-200" @click="cancelPlan">Cancel Plan</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12">
                <div class="mb-4 flex items-center gap-2">
                    <span class="text-xl font-semibold text-blue-700">All Subscriptions</span>
                </div>
                <n-data-table
                    :columns="subscriptionColumns"
                    :data="props.subscriptions.data"
                    :pagination="false"
                    bordered
                    class="rounded-xl shadow"
                    size="small"
                />
                <div class="mt-4 flex justify-end">
                    <n-pagination :page="props.subscriptions.current_page" :page-count="props.subscriptions.last_page" @update:page="changePage" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { Head } from '@inertiajs/vue3';
import { defineComponent, h } from 'vue';
import { useMessage } from 'naive-ui'

const breadcrumbs: BreadcrumbItemType[] = [{ title: 'Company Settings', href: '/company/company-settings' }];
const props = defineProps<{
    company: Record<string, any>;
    subscriptions: {
        data: any[];
        current_page: number;
        last_page: number;
        total: number;
    };
}>();
interface CompanySubscriptionRow {
    id: number;
    start_date: string;
    end_date: string;
    status: string;
    subscription?: {
        name: string;
        price: number;
        currency: string;
    };
}
const subscriptionColumns = [
    {
        title: 'Plan',
        key: 'subscription',
        render: (row: CompanySubscriptionRow) => row.subscription?.name || '-',
    },
    { title: 'Start Date', key: 'start_date' },
    { title: 'End Date', key: 'end_date' },
    {
        title: 'Status',
        key: 'status',
        render: (row: CompanySubscriptionRow) => {
            return row.status === 'active'
                ? h('span', { class: 'text-green-600 font-semibold' }, 'Active')
                : h('span', { class: 'text-red-500 font-semibold' }, row.status || 'Inactive');
        },
    },
    {
        title: 'Price',
        key: 'price',
        render: (row: CompanySubscriptionRow) => (row.subscription ? (row.subscription.currency || '$') + row.subscription.price : '-'),
    },
];

import { router } from '@inertiajs/vue3';
function changePage(page: number) {
    router.get('/company/company-settings', { page }, { preserveScroll: true });
}

// InfoTag (inline functional)
const InfoTag = defineComponent({
    props: {
        label: String,
        type: String,
        value: String,
    },
    setup(props) {
        return () =>
            h('li', { class: 'flex items-center justify-between text-gray-600' }, [
                h('span', { class: 'w-1/2 text-left truncate font-medium' }, props.label + ':'),
                h('span', { class: 'w-1/2 flex justify-end' }, [
                    h('span', {}, [
                        h(
                            'span',
                            {
                                class: [
                                    'inline-block',
                                    'px-2',
                                    'py-1',
                                    'rounded',
                                    'text-xs',
                                    props.type === 'success'
                                        ? 'bg-green-100 text-green-700'
                                        : props.type === 'info'
                                          ? 'bg-blue-100 text-blue-700'
                                          : props.type === 'warning'
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : props.type === 'error'
                                              ? 'bg-red-100 text-red-700'
                                              : 'bg-gray-100 text-gray-700',
                                ],
                            },
                            props.value || '--',
                        ),
                    ]),
                ]),
            ]);
    },
});

const message = useMessage()

function cancelPlan() {
    if (confirm('Are you sure you want to cancel your subscription?')) {
        router.post('/subscriptions/cancel', {}, {
            onSuccess: () => message.success('Subscription cancellation requested.'),
            onError: () => message.error('Failed to cancel subscription.'),
        })
    }
}

function goHome() {
    router.visit('/#pricing');
}
</script>

<!-- No extra style needed! -->
