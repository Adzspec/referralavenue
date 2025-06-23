<template>
    <Head title="Menus" />
    <AppLayout>
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-bold">Menus</h1>
                <NButton type="primary" @click="openCreate">Add Menu</NButton>
            </div>

            <n-data-table
                :columns="columns"
                :data="props.menus"
                :pagination="false"
                class="rounded shadow"
            />

            <CreateMenuModal
                v-if="showCreate"
                :show="showCreate"
                :parent-menus="props.parentMenus"
                @close="closeModals"
            />

            <EditMenuModal
                v-if="showEditModal"
                :show="showEditModal"
                :menu="selectedMenu"
                :parent-options="parentOptions"
                @close="closeEditModal"
                @updated="refreshMenus"
            />
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref, h } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import EditMenuModal from '@/components/menus/EditMenuModal.vue';
import { NDataTable, NButton, useDialog, useMessage } from 'naive-ui';
import CreateMenuModal from '@/components/menus/CreateMenuModal.vue';

const props = defineProps<{
    menus: Array<any>;
    parentMenus: Array<{ id: number; title: string }>;
}>();

const dialog = useDialog();
const message = useMessage();

// Modal control
const showCreate = ref(false)
const showEditModal = ref(false);
const selectedMenu = ref(null);

// Parent menu options for dropdown
const parentOptions = props.parentMenus.map(menu => ({
    label: menu.title,
    value: menu.id,
}));
const openCreate = () => (showCreate.value = true)
const openEditModal = (menu: any) => {
    selectedMenu.value = menu;
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    selectedMenu.value = null;
};
const closeModals = () => {
    showCreate.value = false
    showEditModal.value = false
    selectedMenu.value = null
}
const handleDelete = (id: number) => {
    dialog.warning({
        title: 'Are you sure?',
        content: 'This will permanently delete the menu.',
        positiveText: 'Delete',
        negativeText: 'Cancel',
        onPositiveClick: () => {
            router.delete(`/menus/${id}`, {
                onSuccess: () => message.success('Menu deleted successfully'),
            });
        },
    });
};

const refreshMenus = () => {
    router.reload({ only: ['menus'] });
};

const columns = [
    { title: 'Title', key: 'title' },
    { title: 'Href', key: 'href' },
    { title: 'Icon', key: 'icon' },
    // { title: 'Role', key: 'role' },
    { title: 'Permission', key: 'permission' },
    {
        title: 'Parent',
        key: 'parent_id',
        render: (row: { parent?: { title: string } }) => row.parent?.title || '—'
    },
    {
        title: 'Order',
        key: 'order'
    },
    {
        title: 'Actions',
        key: 'actions',
        render(row: any) {
            return h('div', { class: 'flex gap-2' }, [
                h(
                    NButton,
                    {
                        type: 'primary',
                        size: 'small',
                        onClick: () => openEditModal(row),
                    },
                    { default: () => 'Edit' }
                ),
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
</script>


