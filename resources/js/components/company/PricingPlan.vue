<template>
    <section class="bg-gradient-to-br from-blue-50 to-white py-16 px-4 md:px-20">
        <!-- Header -->
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800">
                Choose Your <br /><span class="text-blue-600">Best</span> Pricing Plan
            </h2>
            <p class="text-gray-500 mt-4 text-lg">
                Great Things In Business Are Never Done <br />
                By One Person
            </p>

            <!-- Billing Toggle -->
            <div class="mt-6 flex justify-center gap-4">
                <label class="inline-flex items-center space-x-2">
                    <input
                        type="radio"
                        name="billing"
                        class="form-radio text-blue-600"
                        checked
                    />
                    <span
                        class="bg-blue-100 text-blue-600 font-medium px-3 py-1 rounded-md text-lg"
                    >$59 Monthly billing</span
                    >
                </label>
                <label class="inline-flex items-center space-x-2">
                    <input
                        type="radio"
                        name="billing"
                        class="form-radio text-blue-600"
                    />
                    <span
                        class="bg-blue-600 text-white font-medium px-3 py-1 rounded-md text-lg"
                    >$99 Annual billing</span
                    >
                </label>
            </div>
        </div>

        <!-- Main Section -->
        <div class="grid grid-cols-1 lg:grid-cols-5 max-w-6xl mx-auto shadow-lg rounded-lg overflow-hidden">
            <!-- Payment Form -->
            <div class="lg:col-span-2 bg-white p-6 sm:p-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Sign In</h3>
                <h4 class="text-2xl font-bold text-gray-800 mb-6">Setup your payment</h4>

                <form class="space-y-4">
                    <input
                        type="email"
                        placeholder="name@email.com"
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                    <input
                        type="password"
                        placeholder="Enter your password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <input
                            type="checkbox"
                            id="terms"
                            class="form-checkbox text-blue-600"
                        />
                        <label for="terms">I agree to terms and conditions.</label>
                    </div>
                    <button
                        type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-md hover:bg-blue-700 transition"
                    >
                        Purchase now
                    </button>
                </form>
            </div>

            <!-- Info Card -->
            <div class="lg:col-span-3 bg-blue-500 text-white p-6 sm:p-8 flex flex-col justify-center relative">
                <div class="max-w-lg">
                    <p class="uppercase text-sm tracking-wide mb-2">Various Analysis Options</p>
                    <h3 class="text-2xl sm:text-3xl font-bold leading-tight">
                        Techno <br />
                        Provides <br />
                        Realtime Best <br />
                        Data Solutions.
                    </h3>
                </div>
                <img
                    src="https://wp.alithemes.com/html/monst/assets/imgs/illustrations/online-shopping.png"
                    alt="Person Illustration"
                    class="w-40 h-auto mx-auto mt-6
         sm:w-48 sm:h-auto
         md:w-56 md:h-auto
         lg:absolute lg:right-4 lg:bottom-4 lg:w-[158px] lg:h-[328px]"
                />

            </div>
        </div>

    </section>
</template>
<script setup lang="ts">
</script>
