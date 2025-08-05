<template>
    <div class="p-6 pt-16 pb-16 rounded-lg mx-auto">
        <div
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-center"
        >
            <div class="text-center">
                <div class="flex justify-center items-center gap-2 mb-2">
                    <svg
                        class="w-8 h-8 text-blue-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                        ></path>
                    </svg>
                    <div
                        class="font-bold text-2xl text-blue-600 count-up"
                        data-target="150"
                    >
                        0
                    </div>
                </div>
                <div class="text-gray-700">Affiliate Partners</div>
            </div>

            <div class="text-center">
                <div class="flex justify-center items-center gap-2 mb-2">
                    <svg
                        class="w-8 h-8 text-blue-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"
                        ></path>
                    </svg>
                    <div
                        class="font-bold text-2xl text-blue-600 count-up"
                        data-target="56"
                    >
                        0
                    </div>
                </div>
                <div class="text-gray-700">Affiliate deals</div>
            </div>

            <div class="text-center">
                <div class="flex justify-center items-center gap-2 mb-2">
                    <svg
                        class="w-8 h-8 text-blue-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"
                        ></path>
                    </svg>
                    <div
                        class="font-bold text-2xl text-blue-600 count-up"
                        data-target="500"
                    >
                        0
                    </div>
                </div>
                <div class="text-gray-700">Publisher partnerships</div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { onMounted } from 'vue'

export default {
    name: 'Contact',
    setup() {
        onMounted(() => {
            const counters = document.querySelectorAll(".count-up")

            counters.forEach((counter) => {
                const updateCount = () => {
                    const target = +counter.getAttribute("data-target")!
                    const count = +counter.textContent!
                    const increment = target / 200

                    if (count < target) {
                        counter.textContent = Math.ceil(count + increment).toString()
                        setTimeout(updateCount, 10)
                    } else {
                        counter.textContent =
                            target >= 1000 ? `+${(target / 1000).toFixed(1)}k` : `+${target}`
                    }
                }

                updateCount()
            })
        })
    },
}
</script>

