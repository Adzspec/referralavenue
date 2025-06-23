<script setup lang="ts">
import { ref } from 'vue';
import { NModal, NForm, NFormItem, NSelect, NDatePicker, NButton } from 'naive-ui';
import { useForm } from '@inertiajs/vue3';

defineProps<{
    show: boolean;
    companies: { id: number; name: string }[];
    subscriptions: { id: number; name: string; price: string }[];
}>();

const emit = defineEmits(['close']);

const form = useForm({
    company_id: null,
    subscription_id: null,
    start_date: null,
    end_date: null,
    status: 'active',
});

const handleSubmit = () => {
    form.post('/company_subscriptions', {
        preserveScroll: true,
        onSuccess: () => {
            emit('close');
            form.reset();
        },
    });
};
</script>

<template>
    <n-modal :show="show" title="Add Subscription" preset="dialog">
        <n-form :model="form" label-placement="top">
            <n-form-item label="Company">
                <n-select
                    v-model:value="form.company_id"
                    :options="companies.map(c => ({ label: c.name, value: c.id }))"
                    placeholder="Select a company"
                />
            </n-form-item>

            <n-form-item label="Subscription Plan">
                <n-select
                    v-model:value="form.subscription_id"
                    :options="subscriptions.map(s => ({ label: `${s.name} ($${s.price})`, value: s.id }))"
                    placeholder="Select a plan"
                />
            </n-form-item>

            <n-form-item label="Start Date">
                <n-date-picker v-model:formatted-value="form.start_date" value-format="yyyy-MM-dd" type="date" />
            </n-form-item>

            <n-form-item label="End Date">
                <n-date-picker v-model:formatted-value="form.end_date" value-format="yyyy-MM-dd" type="date" />
            </n-form-item>

            <n-form-item label="Status">
                <n-select
                    v-model:value="form.status"
                    :options="[
            { label: 'Active', value: 'active' },
            { label: 'Cancelled', value: 'cancelled' },
            { label: 'Expired', value: 'expired' },
          ]"
                />
            </n-form-item>

            <div class="flex justify-end gap-2 mt-4">
                <n-button @click="emit('close')">Cancel</n-button>
                <n-button type="primary" :loading="form.processing" @click="handleSubmit">Create</n-button>
            </div>
        </n-form>
    </n-modal>
</template>
