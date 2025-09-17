<template>
    <footer class="text-gray-700 ">
        <!-- ✅ Full-width top line -->
        <hr class="w-full h-[1px] bg-gray-300 border-none">

        <!-- Footer content with padding -->
        <div class="py-10 px-6 sm:px-10 md:px-20">
            <div
                class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start md:items-center space-y-8 md:space-y-0 md:space-x-12"
            >
                <!-- Logo & Tagline -->
                <div
                    class="flex flex-col md:flex-row md:items-center md:space-x-6 w-full md:w-auto"
                >
                    <div class="flex items-center space-x-3 flex-shrink-0">
                        <a :href="route('home')">
                            <img style="width: 300px; height: auto" src="/logo.png" alt="logo" class="h-10 w-10"/>
                        </a>
                    </div>
                    <p
                        class="mt-4 md:mt-0 text-center md:text-left text-gray-400 max-w-xs md:max-w-sm"
                    >
                        Launch, Manage, and
                        <span class="text-blue-600 font-semibold">Grow</span> Your Affiliate Business All in One Place
                    </p>
                </div>

                <!-- Office Info -->
                <div class="w-full md:w-auto text-center md:text-left">
                    <h3 class="font-bold mb-2 text-xl">Office</h3>
                    <p class="text-gray-400 leading-relaxed">
                        359 Hidden Valley<br />Road, NY
                    </p>
                </div>

                <!-- Contact Info -->
                <div class="w-full md:w-auto text-center md:text-left">
                    <h3 class="font-bold mb-2 text-xl">Contacts</h3>
                    <p class="text-gray-400 leading-relaxed">
                        <a href="tel:+46731785828">+46 73 178 58 28</a>
                        <br />
                        <a
                            href="mailto:hello@referralavenue.com"
                            class="text-blue-600 hover:underline"
                        >hello@referralavenue.com</a>
                    </p>
                </div>

                <!-- Social Icons -->
                <div
                    class="flex justify-center md:justify-start space-x-6 text-blue-600 text-xl w-full md:w-auto"
                >
                    <a href="#" aria-label="Facebook" class="hover:text-blue-800">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" aria-label="Twitter" class="hover:text-blue-400">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" aria-label="Instagram" class="hover:text-pink-600">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>

            <!-- Bottom Text -->
            <div class="text-center mt-12 text-gray-500 text-sm sm:text-base">
                © 2025. All rights reserved. Designed by
                <a
                    href="https://adzspec.com"
                    class="underline hover:text-gray-700"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    Adzspec.com
                </a>
            </div>
        </div>
    </footer>
</template>

<script setup lang="ts">
</script>
