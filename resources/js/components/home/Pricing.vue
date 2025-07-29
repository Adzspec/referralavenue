<template>
    <section class="pt-16 bg-slate-100">
        <div id="pricing">
            <h2 class="text-center font-bold text-4xl text-gray-700 pt-8">
                Start saving time today <br />
                and <span class="text-blue-600">choose</span> your best plan
            </h2>
            <p class="text-gray-400 text-center pt-4 text-xl">
                Best for freelance developers who need to <br />save their time
            </p>
        </div>
        <section class=" py-20 px-4">

            <div class="max-w-6xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">

                <!-- Plan 1 -->
                <div class="relative bg-white shadow-lg pt-32 pb-16 px-8 text-center ">
                    <div class="absolute -top-14 left-1/2 transform -translate-x-1/2">
                        <div class="bg-gradient-to-r from-indigo-900 to-purple-600 text-white px-10 py-8 shadow-xl text-center w-60">
                            <h2 class="text-md font-semibold tracking-widest">HATCHLING PLAN</h2>
                            <p class="text-5xl font-bold mt-3">$29</p>
                            <p class="text-sm uppercase tracking-wider mt-2">Per Month</p>
                        </div>
                    </div>
                    <div class="space-y-4 text-left text-gray-700 mt-10">
                        <p class="flex items-center"><span class="text-green-500 mr-2">✔</span> Unlimited Support</p>
                        <p class="flex items-center"><span class="text-green-500 mr-2">✔</span> 5GB Server Space</p>
                        <p class="flex items-center"><span class="text-green-500 mr-2">✔</span> 2 Users per Project</p>
                        <p class="flex items-center"><span class="text-red-500 mr-2">✘</span> Email Integration</p>
                        <p class="flex items-center"><span class="text-red-500 mr-2">✘</span> Unlimited Download</p>
                    </div>
                    <button class="mt-8 w-full bg-purple-700 hover:bg-purple-800 text-white font-semibold py-2 rounded">
                        CHOOSE PLAN
                    </button>
                </div>

                <!-- Plan 2 -->
                <div class="relative bg-white shadow-lg pt-32 pb-16 px-8 text-center">
                    <div class="absolute -top-14 left-1/2 transform -translate-x-1/2">
                        <div class="bg-gradient-to-r from-green-700 to-lime-500 text-white px-10 py-8 shadow-xl text-center w-60">
                            <h2 class="text-md font-semibold tracking-widest">BABY PLAN</h2>
                            <p class="text-5xl font-bold mt-3">$69</p>
                            <p class="text-sm uppercase tracking-wider mt-2">Per Month</p>
                        </div>
                    </div>
                    <div class="space-y-4 text-left text-gray-700 mt-10">
                        <p class="flex items-center"><span class="text-green-500 mr-2">✔</span> Unlimited Support</p>
                        <p class="flex items-center"><span class="text-green-500 mr-2">✔</span> 10GB Server Space</p>
                        <p class="flex items-center"><span class="text-green-500 mr-2">✔</span> 5 Users per Project</p>
                        <p class="flex items-center"><span class="text-green-500 mr-2">✔</span> Email Integration</p>
                        <p class="flex items-center"><span class="text-red-500 mr-2">✘</span> Unlimited Download</p>
                    </div>
                    <button class="mt-8 w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded">
                        CHOOSE PLAN
                    </button>
                </div>

                <!-- Plan 3 -->
                <div class="relative bg-white shadow-lg pt-32 pb-16 px-8 text-center">
                    <div class="absolute -top-14 left-1/2 transform -translate-x-1/2">
                        <div class="bg-gradient-to-r from-orange-700 to-orange-500 text-white px-10 py-8 shadow-xl text-center w-60">
                            <h2 class="text-md font-semibold tracking-widest">PREMIUM PLAN</h2>
                            <p class="text-5xl font-bold mt-3">$99</p>
                            <p class="text-sm uppercase tracking-wider mt-2">Per Month</p>
                        </div>
                    </div>
                    <div class="space-y-4 text-left text-gray-700 mt-10">
                        <p class="flex items-center"><span class="text-green-500 mr-2">✔</span> Unlimited Support</p>
                        <p class="flex items-center"><span class="text-green-500 mr-2">✔</span> 25GB Server Space</p>
                        <p class="flex items-center"><span class="text-green-500 mr-2">✔</span> 10 Users per Project</p>
                        <p class="flex items-center"><span class="text-green-500 mr-2">✔</span> Email Integration</p>
                        <p class="flex items-center"><span class="text-green-500 mr-2">✔</span> Unlimited Download</p>
                    </div>
                    <button class="mt-8 w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 rounded">
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
    id: number | string
    name: string
    price: number | string
    currency: string
    features: string[]
}

const props = defineProps<{
    plans: Plan[],
    user?: any
}>()
function purchasePlan(planId: number | string) {
    if (!props.user) {
        router.visit('/register') // or '/login' as preferred
        return
    }
    router.post('/subscriptions/checkout', { plan_id: planId })
}
console.log(props.plans)
</script>
