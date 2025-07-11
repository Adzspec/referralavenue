<template>
    <section
        class="w-full bg-blue-600 bg-no-repeat bg-cover bg-center py-16 px-4"
    >
        <div class="max-w-4xl mx-auto text-center space-y-6">
            <!-- Heading -->
            <p class="text-3xl sm:text-4xl font-bold text-white">
                Subscribe now to <span class="text-blue-300">Our Newsletter</span
            ><br />
                and get the Coupon code.
            </p>

            <!-- Subheading -->
            <p class="text-lg sm:text-xl text-white">
                All your information is completely confidential
            </p>

            <!-- Input and Button -->
            <form
                class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-6"
            >
                <div
                    class="flex items-center px-4 py-3 border border-blue-300 rounded-md bg-blue-500 w-full sm:w-auto"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-white mr-2"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            d="M2.94 6.94a1.5 1.5 0 011.12-.44h12a1.5 1.5 0 011.12.44l-7.12 5.3a1.5 1.5 0 01-1.88 0L2.94 6.94z"
                        />
                        <path
                            d="M18 8.16V14.5a1.5 1.5 0 01-1.5 1.5H3.5A1.5 1.5 0 012 14.5V8.16l7.12 5.3a3.5 3.5 0 004.76 0L18 8.16z"
                        />
                    </svg>
                    <input
                        type="email"
                        placeholder="Type your e-mail"
                        class="w-full sm:w-64 bg-transparent text-white placeholder-white text-sm focus:outline-none"
                    />
                </div>

                <button
                    type="submit"
                    class="bg-white text-blue-700 font-medium px-6 py-3 rounded-md text-sm hover:bg-blue-700 hover:text-white hover:border hover:border-white transition duration-500"
                >
                    Sign Up
                </button>
            </form>
        </div>
    </section>
</template>
