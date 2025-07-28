<template>
    <section class="pt-16 bg-slate-100">
        <div>
            <h2 class="text-center font-bold text-4xl text-gray-700 pt-8">
                Start saving time today <br />
                and <span class="text-blue-600">choose</span> your best plan
            </h2>
            <p class="text-gray-400 text-center pt-4 text-xl">
                Best for freelance developers who need to <br />save their time
            </p>
        </div>
        <div class="min-h-screen flex items-center justify-center p-4 pb-16">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl w-full">
                <div
                    v-for="plan in plans"
                    :key="plan.id"
                    class="bg-white rounded-lg shadow-sm p-6 text-center border border-gray-200 transform transition duration-300 ease-in-out hover:scale-105 hover:shadow-lg"
                >
                    <h2 class="text-3xl sm:text-4xl font-bold mb-2 mt-6 sm:mt-8">{{ plan.name }}</h2>
                    <p class="text-blue-600 text-2xl sm:text-3xl pt-3 sm:pt-4 font-bold">
                        {{ plan.currency }}{{ plan.price }}
                    </p>
                    <p class="text-gray-500 mb-3 sm:mb-4 pt-3 sm:pt-4 text-sm sm:text-base">
                        user per month
                    </p>
<!--                    <ul class="text-gray-600 space-y-2 mb-4 sm:mb-6 text-sm sm:text-base">-->
<!--                        <li v-for="(feature, i) in plan.features" :key="i">✅ {{ feature }}</li>-->
<!--                    </ul>-->
                    <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4">
                        <button
                            class="bg-blue-500 text-white px-3 sm:px-4 py-1 sm:py-2 rounded hover:bg-blue-600 text-sm sm:text-base"
                            @click="purchasePlan(plan.id)"
                        >
                            Purchase Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
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
