<template>
    <section class="bg-slate-100 pt-16">
        <!-- Heading -->
        <div id="pricing">
            <h2 class="pt-8 text-center text-4xl font-bold text-gray-700">
                Multi-Network  Aggregation Centralize<br />
                Offers  <span class="text-blue-600">from Across</span>  the Affiliate World
            </h2>
            <p class="pt-4 text-center text-xl text-gray-400">
                Seamlessly integrate with hundreds of affiliate networks  <br /> and automatically pull in new offers. Create one central hub for publishers, regardless of network source.
            </p>
        </div>

        <!-- Pricing Cards -->
        <section class="px-4 py-20">
            <div class="mx-auto grid max-w-6xl grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-4">
                <div
                    v-for="(plan, idx) in props.plans"
                    :key="plan.id"
                    class="relative bg-white px-8 pt-32 pb-16 my-10 text-center shadow-lg rounded-[5%]"
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



    <div class="max-w-7xl mx-auto px-4 py-16 overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden rounded-xl">
                <table class="min-w-full table-fixed text-sm text-left">
                    <thead class="bg-white text-gray-800">
                    <tr>
                        <th class="w-1/4 p-4 text-xl sm:text-2xl font-semibold text-left">Features</th>

                        <!-- Basic -->
                        <th class="w-1/4 p-4 text-center">
                            <div class="text-base sm:text-lg font-semibold">Basic</div>
                            <div class="mt-2">
                                <button class="w-full sm:w-auto bg-gray-100 text-gray-800 font-medium px-4 py-2 sm:px-5 rounded-md text-xs sm:text-sm">Start for free</button>
                            </div>
                            <div class="text-xs text-gray-500 mt-2">Best diffing tools<br />available for free forever</div>
                        </th>

                        <!-- Pro + Desktop -->
                        <th class="w-1/4 p-4 text-center relative">
                            <div class="absolute top-2 left-1/2 -translate-x-1/2">
                                <span class="text-[10px] sm:text-xs font-bold bg-green-100 text-green-700 px-2 py-1 sm:px-3 sm:py-2 rounded-full uppercase">Most Popular</span>
                            </div>
                            <div class="text-base sm:text-lg font-semibold mt-8">Pro <span class="text-green-600">+ Desktop</span></div>
                            <div class="mt-2">
                                <button class="w-full sm:w-auto bg-green-500 hover:bg-green-600 text-white font-medium px-4 py-2 sm:px-5 rounded-md text-xs sm:text-sm">Upgrade now</button>
                            </div>
                            <div class="text-xs text-gray-500 mt-2 leading-tight">
                                <div>Pro <span class="text-gray-400">via browser</span></div>
                                <div>Desktop <span class="text-gray-400">for Win/Mac/Linux</span></div>
                            </div>
                        </th>

                        <!-- Enterprise -->
                        <th class="w-1/4 p-4 text-center">
                            <div class="text-base sm:text-lg font-semibold">Enterprise</div>
                            <div class="mt-2">
                                <button class="w-full sm:w-auto bg-gray-800 text-white font-medium px-4 py-2 sm:px-5 rounded-md text-xs sm:text-sm">Upgrade now</button>
                            </div>
                            <div class="text-xs text-gray-500 mt-2 leading-tight">All Pro and Desktop<br />features tailored for teams</div>
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white">

                    <!-- Feature Rows -->
                    <tr>
                        <td class="p-4"><div class="border-b w-4/5">Text and image diff tools</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                    </tr>
                    <tr>
                        <td class="p-4"><div class="border-b w-4/5">Word and PDF diff tools</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                    </tr>
                    <tr>
                        <td class="p-4"><div class="border-b w-4/5">Folder diff tools</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">&nbsp;</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                    </tr>
                    <tr>
                        <td class="p-4"><div class="border-b w-4/5">Advanced text modifiers</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">&nbsp;</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                    </tr>
                    <tr>
                        <td class="p-4"><div class="border-b w-4/5">Real-time diff</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">&nbsp;</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                    </tr>
                    <tr>
                        <td class="p-4"><div class="border-b w-4/5">Diff merging</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">&nbsp;</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                    </tr>
                    <tr>
                        <td class="p-4"><div class="border-b w-4/5">Syntax highlighting</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">&nbsp;</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                    </tr>
                    <tr>
                        <td class="p-4"><div class="border-b w-4/5">PDF exports</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">&nbsp;</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                    </tr>
                    <tr>
                        <td class="p-4"><div class="border-b w-4/5">Secure offline mode</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">&nbsp;</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                    </tr>
                    <tr>
                        <td class="p-4"><div class="border-b w-4/5">Sharing and commenting</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                    </tr>
                    <tr>
                        <td class="p-4"><div class="border-b w-4/5">AI assist in understanding diffs</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                    </tr>
                    <tr>
                        <td class="p-4"><div class="border-b w-4/5">Ads-free experience</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">&nbsp;</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                        <td class="text-center"><div class="border-b w-2/5 text-lg mx-auto">✔️</div></td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

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
