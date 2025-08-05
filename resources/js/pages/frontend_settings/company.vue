<template>
    <Head title="Frontend Settings" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-screen bg-gradient-to-tr from-slate-100 to-blue-50 p-4 md:p-8">
            <!-- Company Info/Edit Modal -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                <!-- Company Info Card -->
                <div>
                    <div class="flex h-full flex-col items-center rounded-2xl bg-white/80 p-6 shadow-md transition hover:shadow-lg">
                        <div class="w-full flex justify-between mb-1">
                            <h4 class="mb-3 text-center text-xl font-semibold break-all text-blue-800">{{ company.name }}</h4>
                            <n-button size="tiny" type="primary" @click="openEditModal">Edit</n-button>
                        </div>
                        <ul class="w-full space-y-2">
                            <InfoTag label="Email" type="success" :value="company.email" />
                            <InfoTag label="Domain" type="info" :value="company.domain" />
                            <InfoTag label="Reg. No" type="warning" :value="company.registration_no" />
                            <InfoTag label="VAT" type="error" :value="company.vat" />
                            <InfoTag label="Status" type="success" :value="company.status ? 'Active' : 'Inactive'" />
                        </ul>
                    </div>
                </div>
                <!-- Profile Card -->
                <div>
                    <div class="flex h-full flex-col items-center rounded-2xl bg-white/80 p-6 shadow-md transition hover:shadow-lg">
                        <div class="w-full flex justify-between mb-1">
                            <h4 class="mb-3 text-center text-xl font-semibold text-blue-700">Company Profile</h4>
                            <n-button size="tiny" type="primary" @click="openProfileModal">Edit</n-button>
                        </div>
                        <ul class="w-full space-y-2">
                            <InfoTag label="Phone" type="info" :value="company.profile?.phone" />
                            <InfoTag label="Address" type="info" :value="company.profile?.address" />
                            <InfoTag label="City" type="info" :value="company.profile?.city" />
                            <InfoTag label="State" type="info" :value="company.profile?.state" />
                            <InfoTag label="Country" type="info" :value="company.profile?.country" />
                            <InfoTag label="Zipcode" type="info" :value="company.profile?.zipcode" />
                        </ul>
                    </div>
                </div>
                <!-- Subscription Card -->
                <div>
                    <div class="flex h-full flex-col items-center rounded-2xl bg-white/80 p-6 shadow-md transition hover:shadow-lg">
                        <h4 class="mb-3 text-center text-xl font-semibold text-orange-700">Subscription Info</h4>
                        <ul class="w-full space-y-2">
                            <InfoTag label="Current Subscription" type="info" :value="company.latest_subscription?.subscription?.name" />
                            <InfoTag label="Start Date" type="success" :value="company.latest_subscription?.start_date" />
                            <InfoTag label="Expiry Date" type="error" :value="company.latest_subscription?.end_date" />
                            <InfoTag label="Status" type="success" :value="company.latest_subscription?.status ? 'Active' : 'Inactive'" />
                        </ul>
                        <div class="mt-6 flex w-full justify-end gap-2">
                            <n-button type="info" @click="goHome">
                                Upgrade Plan
                            </n-button>
                            <n-button v-if="company.latest_subscription?.status==='active'" type="error"  @click="cancelPlan">Cancel Plan</n-button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- All Subscriptions Table -->
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

            <!-- Edit Company Modal -->
            <n-modal v-model:show="showEditModal" preset="dialog" title="Edit Company Info" style="min-width: 350px;">
                <n-form :model="editForm" label-placement="top">
                    <n-form-item label="Company Name" path="name">
                        <n-input v-model:value="editForm.name" />
                    </n-form-item>
                    <n-form-item label="Email" path="email">
                        <n-input v-model:value="editForm.email" />
                    </n-form-item>
                    <n-form-item label="Domain" path="domain">
                        <n-input v-model:value="editForm.domain" />
                    </n-form-item>
                    <n-form-item label="Registration No" path="registration_no">
                        <n-input v-model:value="editForm.registration_no" />
                    </n-form-item>
                    <n-form-item label="VAT" path="vat">
                        <n-input v-model:value="editForm.vat" />
                    </n-form-item>
                    <n-space justify="end" class="mt-2">
                        <n-button @click="showEditModal = false">Cancel</n-button>
                        <n-button type="primary" :loading="savingEdit" @click="saveCompany(company.id)">Save</n-button>
                    </n-space>
                </n-form>
            </n-modal>
            <!-- Edit Profile Modal -->
            <n-modal v-model:show="showProfileModal" preset="dialog" title="Edit Company Profile" style="min-width: 350px;">
                <n-form :model="profileForm" label-placement="top">
                    <n-form-item label="Phone" path="phone">
                        <n-input placeholder="Enter Company Phone Number" v-model:value="profileForm.phone" />
                    </n-form-item>
                    <n-form-item label="Address" path="address">
                        <n-input placeholder="Enter Company Address" v-model:value="profileForm.address" />
                    </n-form-item>
                    <n-form-item label="City" path="city">
                        <n-input placeholder="Enter City" v-model:value="profileForm.city" />
                    </n-form-item>
                    <n-form-item label="State" path="state">
                        <n-input placeholder="Enter State" v-model:value="profileForm.state" />
                    </n-form-item>
                    <n-form-item label="Country" path="country">
                        <n-input placeholder="Enter Country" v-model:value="profileForm.country" />
                    </n-form-item>
                    <n-form-item label="Zipcode" path="zipcode">
                        <n-input placeholder="Enter Placeholder" v-model:value="profileForm.zipcode" />
                    </n-form-item>
                    <n-space justify="end" class="mt-2">
                        <n-button @click="showProfileModal = false">Cancel</n-button>
                        <n-button type="primary" :loading="savingProfile" @click="saveProfile(company.id)">Save</n-button>
                    </n-space>
                </n-form>
            </n-modal>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { Head } from '@inertiajs/vue3';
import { defineComponent, h, reactive, ref } from 'vue';
import { useMessage, useDialog } from 'naive-ui';
import { router } from '@inertiajs/vue3';

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

// Utility Card Tag
const InfoTag = defineComponent({
    props: { label: String, type: String, value: String },
    setup(props) {
        return () =>
            h('li', { class: 'flex items-center justify-between text-gray-600' }, [
                h('span', { class: 'w-1/2 text-left truncate font-medium' }, props.label + ':'),
                h('span', { class: 'w-1/2 flex justify-end' }, [
                    h(
                        'span',
                        {
                            class: [
                                'inline-block', 'px-2', 'py-1', 'rounded', 'text-xs',
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
            ]);
    },
});

// State for modals
const showEditModal = ref(false);
const showProfileModal = ref(false);

// Edit Company Info
const editForm = reactive({
    name: props.company.name,
    email: props.company.email,
    domain: props.company.domain,
    registration_no: props.company.registration_no,
    vat: props.company.vat,
    status: props.company.status,
});
const savingEdit = ref(false);
function openEditModal() {
    // Optionally, reload values from props
    Object.assign(editForm, {
        name: props.company.name,
        email: props.company.email,
        domain: props.company.domain,
        registration_no: props.company.registration_no,
        vat: props.company.vat,
        status: props.company.status,
    });
    showEditModal.value = true;
}
function saveCompany(id: any) {
    savingEdit.value = true;
    router.put(`/companies/${id}`, editForm, {
        onSuccess: () => {
            message.success('Company updated!');
            showEditModal.value = false;
            savingEdit.value = false;
        },
        onError: () => {
            message.error('Failed to update');
            savingEdit.value = false;
        },
    });
}

// Edit Company Profile
const profileForm = reactive({
    phone: props.company.profile?.phone || '',
    address: props.company.profile?.address || '',
    city: props.company.profile?.city || '',
    state: props.company.profile?.state || '',
    country: props.company.profile?.country || '',
    zipcode: props.company.profile?.zipcode || '',
});
const savingProfile = ref(false);
function openProfileModal() {
    Object.assign(profileForm, {
        phone: props.company.profile?.phone || '',
        address: props.company.profile?.address || '',
        city: props.company.profile?.city || '',
        state: props.company.profile?.state || '',
        country: props.company.profile?.country || '',
        zipcode: props.company.profile?.zipcode || '',
    });
    showProfileModal.value = true;
}
function saveProfile(id: any) {
    savingProfile.value = true;
    router.put(`/companies/${id}/profile`, profileForm, {
        onSuccess: () => {
            message.success('Profile updated!');
            showProfileModal.value = false;
            savingProfile.value = false;
        },
        onError: () => {
            message.error('Failed to update profile');
            savingProfile.value = false;
        },
    });
}

// Subscription Table
interface CompanySubscriptionRow {
    id: number;
    start_date: string;
    end_date: string;
    status: string;
    subscription?: { name: string; price: number; currency: string };
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
            return row.status === '1'
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

const message = useMessage();
const dialog = useDialog();
function cancelPlan() {
    dialog.warning({
        title: 'Cancel Subscription',
        content: 'Are you sure you want to cancel your subscription?',
        positiveText: 'Yes, Cancel',
        negativeText: 'No',
        maskClosable: false,
        onPositiveClick: () => {
            router.post('/subscriptions/cancel', {}, {
                onSuccess: () => message.success('Subscription cancellation requested.'),
                onError: () => message.error('Failed to cancel subscription.'),
            })
        }
    })
}

function goHome() {
    router.visit('/#pricing');
}
function changePage(page: number) {
    router.get('/company/company-settings', { page }, { preserveScroll: true });
}
</script>
