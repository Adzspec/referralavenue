<template>
    <Head title="Subscription Features" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <h1 class="mb-4 text-2xl font-bold">🛠 Feature Matrix</h1>

            <n-form @submit.prevent="submit">
                <n-table bordered striped>
                    <thead>
                        <tr>
                            <th>Feature</th>
                            <th v-for="plan in props.plans" :key="plan.id">{{ plan.name }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="feature in props.features" :key="feature.id">
                            <td>{{ feature.name }}</td>
                            <td v-for="plan in props.plans" :key="`${plan.id}-${feature.id}`">
                                <n-input
                                    v-if="feature.is_value_based"
                                    v-model:value="matrix[plan.id][feature.id]"
                                    placeholder="Enter value"
                                    size="small"
                                />
                                <n-checkbox v-else v-model:checked="matrix[plan.id][feature.id]" />
                            </td>
                        </tr>
                    </tbody>
                </n-table>
                <div class="mt-4">
                    <n-button type="primary" class="mt-4 pt-4" @click="submit">💾 Save Matrix</n-button>
                </div>
            </n-form>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { useMessage } from 'naive-ui';
import { ref } from 'vue';
import type { BreadcrumbItemType } from '@/types';

const breadcrumbs: BreadcrumbItemType[] = [
    { title: 'Subscription Features', href: '/feature-matrix' }
];
interface Feature {
    id: number;
    name: string;
    key: string;
    is_value_based: boolean;
}

interface Plan {
    id: number;
    name: string;
    feature_values: {
        feature: Feature;
        value: string;
    }[];
}

const props = withDefaults(
    defineProps<{
        plans: Plan[];
        features: Feature[];
    }>(),
    {
        plans: () => [],
        features: () => [],
    },
);

const matrix = ref<Record<number, Record<number, string | boolean>>>({});

for (const plan of props.plans) {
    matrix.value[plan.id] = {};

    for (const feature of props.features) {
        const existing = plan.feature_values.find((fv) => fv.feature.id === feature.id);

        matrix.value[plan.id][feature.id] = existing
            ? feature.is_value_based
                ? existing.value
                : existing.value === '1'
            : feature.is_value_based
              ? ''
              : false;
    }
}

const message = useMessage();

function submit() {
    router.post(
        route('feature-matrix.update'),
        {
            matrix: matrix.value,
        },
        {
            onSuccess: () => {
                message.success('Feature matrix updated successfully!');
            },
            onError: (errors) => {
                message.error('Failed to update feature matrix. Please check and try again.');
                console.error(errors);
            },
        },
    );
}
</script>
