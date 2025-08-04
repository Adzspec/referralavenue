<template>
    <Head title="Subscription Success" />
    <AppLayout>
        <div class="flex flex-col items-center justify-center h-96">
            <n-result
                status="success"
                title="Thank you for your purchase!"
                description="Your subscription is activated."
            />
            <n-button
                type="primary"
                class="mt-6"
                @click="goDashboard"
            >
                Go to Dashboard
            </n-button>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

function goDashboard() {
    router.visit('/dashboard');
}
</script>
