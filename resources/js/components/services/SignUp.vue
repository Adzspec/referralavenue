<template>
    <section class="w-full bg-no-repeat bg-center bg-cover pt-16 pb-16 bg-blue-600">
        <div class="max-w-4xl mx-auto px-4 flex flex-col items-center justify-center text-center space-y-6">

            <!-- Heading -->
            <p class="text-2xl sm:text-3xl md:text-4xl font-bold text-white leading-snug">
                Subscribe now to <span class="text-blue-300">Our Newsletter</span><br />
                and get the Coupon code.
            </p>

            <!-- Subtext -->
            <p class="text-base sm:text-lg text-white">
                All your information is completely confidential
            </p>

            <!-- Form -->
            <div class="w-full flex flex-col sm:flex-row items-center justify-center gap-4 mt-4">

                <!-- Email Input -->
                <div class="flex items-center w-full sm:w-auto px-4 py-3 border border-blue-400 space-x-2 rounded-md bg-transparent">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-300" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2.94 6.94a1.5 1.5 0 011.12-.44h12a1.5 1.5 0 011.12.44l-7.12 5.3a1.5 1.5 0 01-1.88 0L2.94 6.94z" />
                        <path d="M18 8.16V14.5a1.5 1.5 0 01-1.5 1.5H3.5A1.5 1.5 0 012 14.5V8.16l7.12 5.3a3.5 3.5 0 004.76 0L18 8.16z" />
                    </svg>
                    <input
                        type="email"
                        placeholder="Type your e-mail"
                        class="text-white bg-transparent focus:outline-none w-full sm:w-64 text-sm placeholder:text-white"
                    />
                </div>

                <!-- Sign Up Button -->
                <button
                    class="bg-white rounded-md hover:bg-blue-700 hover:text-white border hover:border-blue-300 transition duration-700 text-blue-700 px-6 py-3 text-sm w-full sm:w-auto"
                >
                    Sign Up
                </button>
            </div>
        </div>
    </section>
</template>
