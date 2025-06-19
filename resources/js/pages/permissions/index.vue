<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, h } from 'vue';
import { NButton, NDataTable, NModal, NInput, useMessage, useDialog, NPagination } from 'naive-ui';

const props = defineProps<{
    permissions: {
        data: Array<{
            id: number; name: string
        }>;
        current_page: number;
        last_page: number;
        total: number;
        per_page: number;
        from: number;
        to: number;
    };
    can: { create: boolean; edit: boolean; delete: boolean };
}>();

const showModal = ref(false);
const editing = ref(false);
const form = ref({ id: null, name: '' });

const message = useMessage();
const dialog = useDialog();

function openCreate() {
    form.value = { id: null, name: '' };
    editing.value = false;
    showModal.value = true;
}

function openEdit(permission:any) {
    form.value = { id: permission.id, name: permission.name };
    editing.value = true;
    showModal.value = true;
}

function save() {
    const url = editing.value
        ? route('permissions.update', form.value.id)
        : route('permissions.store');

    const method = editing.value ? 'put' : 'post';

    router.visit(url, {
        method,
        data: { name: form.value.name },
        preserveScroll: true,
        onSuccess: () => {
            showModal.value = false;
            message.success(`Permission ${editing.value ? 'updated' : 'created'} successfully`);
        },
    });
}

const handleDelete = (id: number) => {
    dialog.warning({
        title: 'Confirm Deletion',
        content: 'Are you sure you want to delete this role?',
        positiveText: 'Yes',
        negativeText: 'Cancel',
        onPositiveClick: () => {
            router.delete(`/permissions/${id}`);
        },
    });
};

const columns = [
    { title: 'Name', key: 'name' },
    {
        title: 'Actions',
        key: 'actions',
        render(row:any) {
            return h('div', { class: 'flex gap-2' }, [
                props.can.edit &&
                h(
                    NButton,
                    { size: 'small', type: 'primary', onClick: () => openEdit(row) },
                    { default: () => 'Edit' }
                ),
                props.can.delete &&
                h(
                    NButton,
                    { size: 'small', type: 'error', onClick: () => handleDelete(row.id) },
                    { default: () => 'Delete' }
                ),
            ]);
        },
    },
];
const handlePageChange = (newPage: number) => {
    router.get('/permissions', { page: newPage }, {
        preserveScroll: true,
        preserveState: true,
    });
};
</script>

<template>
    <Head title="Permissions" />
    <AppLayout>
        <div class="p-6">
            <div class="flex justify-between items-center pb-4">
                <h1 class="text-2xl font-semibold">Permissions</h1>
                <n-button v-if="props.can.create" type="primary" @click="openCreate">Add Permission</n-button>
            </div>

            <n-data-table :columns="columns" :data="props.permissions.data" class="bg-white rounded shadow" />
            <div class="mt-6 flex justify-end">
                <n-pagination
                    :page="props.permissions.current_page"
                    :page-count="props.permissions.last_page"
                    @update:page="handlePageChange"
                />
            </div>

            <n-modal v-model:show="showModal" preset="dialog" title="Permission">
                <div class="space-y-4">
                    <n-input v-model:value="form.name" placeholder="Permission name" />
                    <div class="flex justify-end gap-2">
                        <n-button @click="showModal = false">Cancel</n-button>
                        <n-button type="primary" @click="save">{{ editing ? 'Update' : 'Create' }}</n-button>
                    </div>
                </div>
            </n-modal>
        </div>
    </AppLayout>
</template>
