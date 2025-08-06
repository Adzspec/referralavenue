<template>
    <section class="bg-slate-100 pt-16 pb-24">
        <!-- Heading -->
        <div id="pricing" class="px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-700">
                Multi-Network Aggregation Centralize<br />
                Offers <span class="text-blue-600">from Across</span> the Affiliate World
            </h2>
            <p class="mt-4 text-base md:text-xl text-gray-500 max-w-3xl mx-auto">
                Seamlessly integrate with hundreds of affiliate networks and automatically pull in new offers.
                Create one central hub for publishers, regardless of network source.
            </p>
        </div>

        <!-- Pricing Cards/Table -->
        <div class="mt-16 px-4">
            <div class="max-w-7xl mx-auto overflow-x-auto">
                <table class="min-w-full table-fixed text-sm bg-white rounded-xl shadow overflow-hidden">
                    <thead class="bg-white text-gray-800">
                    <tr>
                        <th class="w-1/4 p-4 text-left text-xl font-semibold">Features</th>
                        <th
                            v-for="plan in plans"
                            :key="plan.id"
                            class="w-1/5 p-4 text-center border-l"
                        >
                            <div class="text-lg font-bold text-gray-700">{{ plan.name }}</div>
                            <div class="mt-1 text-sm text-gray-500">$ {{ plan.price }}</div>
                            <button
                                @click="purchasePlan(plan.id)"
                                class="cursor-pointer mt-3 inline-block rounded-md bg-green-500 px-5 py-2 text-xs font-medium text-white hover:bg-green-600 transition"
                            >
                                Upgrade now
                            </button>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                        v-for="feature in features"
                        :key="feature.id"
                        class="border-t"
                    >
                        <td class="p-4 text-gray-700 font-medium">
                            {{ feature.name }}
                        </td>

                        <td
                            v-for="plan in plans"
                            :key="`${plan.id}-${feature.id}`"
                            class="p-4 text-center text-lg text-gray-700 border-b"
                        >
                            {{ getFeatureValue(plan, feature.id, feature.is_value_based) }}
                        </td>
                    </tr>
                    </tbody>

                </table>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3';

interface Plan {
    id: number | string;
    name: string;
    price: number | string;
    currency?: string;
    feature_values: FeatureValue[];
}

interface Feature {
    id: number;
    name: string;
    key: string;
    is_value_based: boolean;
}

interface FeatureValue {
    feature_id: number;
    value: string;
}

const props = defineProps<{
    plans: Plan[];
    features: Feature[];
    user?: any;
}>();

function getFeatureValue(plan: Plan, featureId: number, isValueBased: boolean): string {
    const values = plan.feature_values || [];
    const match = values.find((fv) => fv.feature_id === featureId);

    if (!match || !match.value) return '❌';
    return isValueBased ? match.value : match.value === '1' ? '✔' : '❌';
}

function purchasePlan(planId: number | string) {
    if (!props.user) {
        router.visit('/register');
        return;
    }
    router.post('/subscriptions/checkout', { plan_id: planId });
}
</script>
