<template>
    <section class="bg-slate-100 pt-16">
        <!-- Heading -->
        <div id="pricing">
            <h2 class="pt-8 text-center text-4xl font-bold text-gray-700">
                Start saving time today <br />
                and <span class="text-blue-600">choose</span> your best plan
            </h2>
            <p class="pt-4 text-center text-xl text-gray-400">
                Best for freelance developers who need to <br />save their time
            </p>
        </div>

        <!-- Pricing Cards -->
        <section class="px-4 py-20">
            <div class="mx-auto grid max-w-6xl grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="(plan, idx) in props.plans"
                    :key="plan.id"
                    :class="[
            'relative bg-white px-8 pt-32 pb-16 py-5 text-center shadow-lg rounded-[5%]',
            idx === 3 ? 'mt-10' : ''
          ]"
                >
                    <!-- Floating Label -->
                    <div class="absolute -top-14 left-1/2 -translate-x-1/2 transform">
                        <div
                            class="w-60 px-10 py-8 text-center text-white shadow-xl rounded-4xl"
                            :class="getPlanGradient(idx)"
                        >
                            <h2 class="text-sm font-semibold tracking-widest uppercase">
                                {{ plan.name }}
                            </h2>
                            <p class="mt-3 text-3xl font-bold">
                                {{ plan.currency || 'SEK ' }}{{ plan.price }}
                            </p>
                            <p class="mt-2 text-sm tracking-wider uppercase">Per Month</p>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="mt-10 space-y-4 text-left text-gray-700">
                        <p
                            v-for="(feature, i) in plan.features"
                            :key="i"
                            class="flex items-center"
                        >
                            <span class="mr-2 text-green-500">✔</span>
                            {{ feature }}
                        </p>
                    </div>

                    <!-- Button -->
                    <button
                        class="mt-8 w-full rounded-2xl py-2 font-semibold text-white"
                        :class="getPlanButtonColor(idx)"
                        @click="purchasePlan(plan.id)"
                    >
                        CHOOSE PLAN
                    </button>
                </div>
            </div>
        </section>
    </section>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3';

interface Plan {
    id: number | string;
    name: string;
    price: number | string;
    currency?: string;
    features: string[];
}

const props = defineProps<{
    plans: Plan[];
    user?: any;
}>();

function purchasePlan(planId: number | string) {
    if (!props.user) {
        router.visit('/register');
        return;
    }
    router.post('/subscriptions/checkout', { plan_id: planId });
}

function getPlanGradient(idx: number) {
    if (idx === 0) return 'bg-gradient-to-r from-indigo-900 to-purple-600';
    if (idx === 1) return 'bg-gradient-to-r from-green-700 to-lime-500';
    if (idx === 2) return 'bg-gradient-to-r from-orange-700 to-orange-500';
    return 'bg-gradient-to-r from-slate-700 to-slate-400';
}

function getPlanButtonColor(idx: number) {
    if (idx === 0) return 'bg-purple-700 hover:bg-purple-800';
    if (idx === 1) return 'bg-green-600 hover:bg-green-700';
    if (idx === 2) return 'bg-orange-500 hover:bg-orange-600';
    return 'bg-gray-600 hover:bg-gray-700';
}
</script>
