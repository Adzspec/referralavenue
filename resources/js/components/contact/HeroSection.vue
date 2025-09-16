<template>
    <!-- Contact Us Hero -->
    <section class="bg-gradient-to-r from-blue-50 to-indigo-50 py-16 px-6 md:px-16">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">

            <!-- Left content -->
            <div>
                <!-- Breadcrumb -->
                <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
                    <span class="hover:text-indigo-600 cursor-pointer">Home</span>
                    <span class="text-gray-400">></span>
                    <span class="font-medium text-gray-800">Contact Us</span>
                </div>

                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">
                    Get in <span class="text-indigo-600">Touch</span> With Us
                </h1>
                <p class="mt-4 text-gray-600 text-lg leading-relaxed">
                    Have questions or need support? Our team is here to help you 24/7.
                    Reach out and we’ll respond as quickly as possible.
                </p>

                <!-- Buttons -->
                <div class="mt-6 flex flex-wrap gap-4">
                    <a href="#form" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-full font-semibold shadow-md">
                        Send Message
                    </a>
                    <a href="mailto:support@example.com" class="bg-white border border-gray-300 px-6 py-3 rounded-full font-semibold text-gray-700 hover:bg-gray-50">
                        Email Us
                    </a>
                </div>

                <!-- Stats -->
                <div class="mt-10 grid grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl shadow p-5 text-center">
                        <p class="text-2xl font-bold text-indigo-600">24/7</p>
                        <p class="text-gray-500 text-sm">Support</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5 text-center">
                        <p class="text-2xl font-bold text-blue-600">1h</p>
                        <p class="text-gray-500 text-sm">Avg. Response</p>
                    </div>
                </div>
            </div>

            <!-- Right illustration -->
            <div class="flex justify-center md:justify-end">
                <div class="bg-gradient-to-tr from-indigo-500 to-blue-500 rounded-3xl shadow-2xl p-8 w-full max-w-md">
                    <div class="text-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m0 0l4-4m-4 4l4 4M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" />
                        </svg>
                        <h3 class="text-xl font-bold mb-2">We’re Here to Help</h3>
                        <p class="text-sm text-indigo-100">Contact our friendly team for any queries or assistance.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>
</template>

<script setup lang="ts">
</script>
