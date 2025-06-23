<template>
    <Head title="Users" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="flex justify-between items-center pb-2">
                <h1 class="text-2xl font-semibold">Users</h1>
                <n-button v-if="props.can.create" type="primary" @click="openCreateModal">Add User</n-button>
            </div>
            <n-data-table
                :columns="columns"
                :data="props.users.data"
                :pagination="false"
                striped
                class="bg-white rounded-lg shadow"
            />

            <div class="mt-6 flex justify-end">
                <n-pagination
                    :page="props.users.current_page"
                    :page-count="props.users.last_page"
                    @update:page="handlePageChange"
                />
            </div>
            <EditUserModal
                :show="showEditModal"
                :roles="props.roles"
                :user="selectedUser"
                @close="closeEditModal"
            />
            <CreateUserModal
                v-if="showCreateModal"
                :show="showCreateModal"
                :roles="props.roles"
                @close="closeCreateModal"
                @created="handleUserCreated"
            />
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { NDataTable, NButton, NPagination } from 'naive-ui';
import { h } from 'vue';
import type { BreadcrumbItem } from '@/types';
import { ref } from 'vue';
import EditUserModal from '@/components/users/EditUserModal.vue';
import CreateUserModal from '@/components/users/CreateUserModal.vue';
import { useDialog } from 'naive-ui';

const props = defineProps<{
    users: {
        data: Array<{
            id: number;
            name: string;
            email: string;
            roles?: Array<{ name: string }>;
        }>;
        current_page: number;
        last_page: number;
        total: number;
        per_page: number;
        from: number;
        to: number;
    };
    roles: Array<{ id: number; name: string }>;
    can: { create: boolean; edit: boolean; delete: boolean };
}>();
const showEditModal = ref(false);
const selectedUser = ref(null);

const openEditModal = (user:any) => {
    selectedUser.value = user;
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    selectedUser.value = null;
};

const showCreateModal = ref(false);

const openCreateModal = () => {
    showCreateModal.value = true;
};

const closeCreateModal = () => {
    showCreateModal.value = false;
};

const dialog = useDialog();


const columns = [
    {
        title: 'Name',
        key: 'name',
    },
    {
        title: 'Email',
        key: 'email',
    },
    {
        title: 'Roles',
        key: 'roles',
        render(row: any) {
            return row.roles?.map((r: any) => r.name).join(', ') || '—';
        }
    },
    {
        title: 'Actions',
        key: 'actions',
        render(row: any) {
            return h('div', { class: 'gap-2 flex items-center' }, [
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
                        onClick: () => confirmDelete(row.id),
                    },
                    { default: () => 'Delete' }
                ),
            ]);
        },
    }
];

const confirmDelete = (id: number) => {
    dialog.warning({
        title: 'Confirm Deletion',
        content: 'Are you sure you want to delete this user?',
        positiveText: 'Yes',
        negativeText: 'No',
        onPositiveClick: () => {
            router.delete(`/users/${id}`);
        },
    });
};


const handlePageChange = (newPage: number) => {
    router.get('/users', { page: newPage }, {
        preserveScroll: true,
        preserveState: true,
    });
};
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
];
const users = ref([...props.users.data]); // Clone the prop data into local state

const handleUserCreated = (newUser:any) => {
    users.value.unshift(newUser); // Safe update, no prop mutation
};


</script>


