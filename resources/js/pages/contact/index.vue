<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { h, computed } from 'vue'
import { NButton, NDataTable, NPagination, useDialog } from 'naive-ui'
import type { DataTableColumns } from 'naive-ui'

const dialog = useDialog()

// ---------- Types ----------
type ContactRow = {
    id: number
    department: 'support' | 'sales'
    subject: string | null
    name: string
    email: string
    message: string
    attachment_path?: string | null
    created_at?: string
}

type Paginated<T> = {
    data: T[]
    current_page: number
    last_page: number
    total: number
    per_page: number
    from: number
    to: number
}

const props = defineProps<{
    messages: Paginated<ContactRow>  // 👈 backend se contact messages
    can: { delete: boolean }
}>()

// ---------- Delete ----------
const handleDelete = (id: number) => {
    dialog.warning({
        title: 'Confirm Deletion',
        content: 'Are you sure you want to delete this message?',
        positiveText: 'Yes',
        negativeText: 'Cancel',
        onPositiveClick: () => {
            // Adjust route if different (e.g., /admin/contact-messages/{id})
            router.delete(`/contact-messages/${id}`, { preserveScroll: true })
        }
    })
}

// ---------- Columns ----------
const columns = computed<DataTableColumns<ContactRow>>(() => [
    { title: 'Date', key: 'created_at',
        render(row) {
            // simple date trim; format in backend if you prefer
            const d = row.created_at?.replace('T', ' ').slice(0, 16) ?? ''
            return h('span', d)
        }
    },
    { title: 'Dept', key: 'department',
        render: (row) => h('span', row.department.toUpperCase())
    },
    { title: 'Name', key: 'name' },
    { title: 'E-Mail', key: 'email' },
    { title: 'Subject', key: 'subject',
        render: (row) => h('span', row.subject ?? '—')
    },
    { title: 'Message', key: 'message',
        width: 320,
        render(row) {
            const text = row.message?.length > 120 ? row.message.slice(0, 120) + '…' : row.message
            return h('span', { title: row.message }, text)
        }
    },
    { title: 'Attachment', key: 'attachment_path',
        render(row) {
            if (!row.attachment_path) return h('span', '—')
            // served via storage symlink => /storage/...
            const url = row.attachment_path.startsWith('http')
                ? row.attachment_path
                : `/storage/${row.attachment_path}`
            return h('a', { href: url, target: '_blank', class: 'text-blue-600 hover:underline' }, 'View')
        }
    },
    {
        title: 'Actions',
        key: 'actions',
        width: 120,
        render(row) {
            return h('div', { class: 'flex gap-2' }, [
                props.can.delete &&
                h(
                    NButton,
                    { size: 'small', type: 'error', onClick: () => handleDelete(row.id) },
                    { default: () => 'Delete' }
                )
            ])
        }
    }
])

// ---------- Pagination ----------
const onPageChange = (page: number) => {
    router.visit(route?.('contact-messages.index') ?? '/contact-messages', {
        method: 'get',
        data: { page },
        preserveScroll: true,
        preserveState: true,
        replace: true
    })
}
</script>

<template>
    <Head title="Contact Messages" />
    <AppLayout>
        <div class="p-6">
            <div class="flex items-center justify-between pb-4">
                <h1 class="text-2xl font-semibold">Contact Us Messages</h1>
            </div>

            <n-data-table
                :columns="columns"
                :data="props.messages.data"
                :pagination="false"
                class="rounded bg-white shadow"
            />

            <div class="mt-6 flex justify-end">
                <n-pagination
                    :page="props.messages.current_page"
                    :page-count="props.messages.last_page"
                    @update:page="onPageChange"
                />
            </div>
        </div>
    </AppLayout>
</template>
