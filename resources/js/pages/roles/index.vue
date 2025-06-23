<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, h } from 'vue';
import { NButton, NDataTable, NPagination, useDialog } from 'naive-ui';
import CreateRoleModal from '@/components/roles/CreateRoleModal.vue';
import EditRoleModal from '@/components/roles/EditRoleModal.vue';

const props = defineProps<{
    roles: {
        data: Array<{
            id: number;
            name: string;
            permissions: { name: string }[];
        }>;
        current_page: number;
        last_page: number;
        total: number;
        per_page: number;
        from: number;
        to: number;
    };
    permissions: {
        id: number;
        name: string;
    }[];
    can: { create: boolean; edit: boolean; delete: boolean };
}>();

const dialog = useDialog();

const showCreateModal = ref(false);
const showEditModal = ref(false);
const selectedRole = ref<{
    id: number;
    name: string;
    permissions: { name: string }[];
} | null>(null);

const openCreate = () => (showCreateModal.value = true);
const openEdit = (role: { id: number; name: string; permissions: { name: string }[] }) => {
    selectedRole.value = role;
    showEditModal.value = true;
};
const closeModals = () => {
    showCreateModal.value = false;
    showEditModal.value = false;
    selectedRole.value = null;
};

const handleDelete = (id: number) => {
    dialog.warning({
        title: 'Confirm Deletion',
        content: 'Are you sure you want to delete this role?',
        positiveText: 'Yes',
        negativeText: 'Cancel',
        onPositiveClick: () => {
            router.delete(`/roles/${id}`);
        },
    });
};

const columns = [
    {
        title: 'Name',
        key: 'name',
    },
    {
        title: 'Permissions',
        key: 'permissions',
        render(row: any) {
            return row.permissions.map((p: any) => p.name).join(', ') || '—';
        },
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
                            size: 'small',
                            type: 'primary',
                            onClick: () => openEdit(row),
                        },
                        { default: () => 'Edit' },
                    ),
                props.can.delete &&
                    h(
                        NButton,
                        {
                            size: 'small',
                            type: 'error',
                            onClick: () => handleDelete(row.id),
                        },
                        { default: () => 'Delete' },
                    ),
            ]);
        },
    },
];
</script>

<template>
    <Head title="Roles" />
    <AppLayout>
        <div class="p-6">
            <div class="flex items-center justify-between pb-4">
                <h1 class="text-2xl font-semibold">Roles</h1>
                <n-button v-if="props.can.create" type="primary" @click="openCreate">Add Role</n-button>
            </div>

            <n-data-table :columns="columns" :data="props.roles.data" :pagination="false" class="rounded bg-white shadow" />

            <div class="mt-6 flex justify-end">
                <n-pagination :page="props.roles.current_page" :page-count="props.roles.last_page" />
            </div>
        </div>

        <CreateRoleModal v-if="showCreateModal" :show="showCreateModal" :permissions="props.permissions" @close="closeModals" />

        <EditRoleModal
            v-if="showEditModal && selectedRole"
            :show="showEditModal"
            :role="selectedRole"
            :permissions="props.permissions"
            @close="closeModals"
        />
    </AppLayout>
</template>
