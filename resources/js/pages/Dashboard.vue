<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    type: string;
    counts: Record<string, number>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const stats = computed(() => Object.entries(props.counts));
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div
                    v-for="[label, value] in stats"
                    :key="label"
                    class="relative flex flex-col items-center justify-center overflow-hidden rounded-xl border border-sidebar-border/70 p-4 text-center dark:border-sidebar-border"
                >
                    <div class="text-2xl font-bold">{{ value }}</div>
                    <div class="mt-2 capitalize text-muted-foreground">
                        {{ label.replace('_', ' ') }}
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
