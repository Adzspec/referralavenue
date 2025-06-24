<template>
    <Head title="Welcome">
<!--        <link rel="preconnect" href="https://rsms.me/" />-->
<!--        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />-->
    </Head>

    <!-- ✅ Make header separate and sticky with z-index and bg -->
    <header class="sticky top-0 z-50 w-full bg-[#FDFDFC] dark:bg-[#0a0a0a] border-b border-gray-200 dark:border-gray-700">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-6 sm:px-6 md:px-6 lg:px-8">
            <!-- Logo -->
            <div class="flex flex-shrink-0 items-center space-x-2">
<!--                <div class="h-6 w-6 rounded-full bg-blue-600"></div>-->
                <span class="text-xl font-semibold text-gray-800">Referalavenue</span>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden items-center space-x-4 text-gray-700 md:flex md:space-x-5 lg:space-x-6">
                <a href="#" class="font-medium whitespace-nowrap transition hover:text-blue-600">Home</a>
                <a href="#" class="font-medium whitespace-nowrap transition hover:text-blue-600">About Us</a>
                <a href="#" class="font-medium whitespace-nowrap transition hover:text-blue-600">Services</a>
                <a href="#" class="font-medium whitespace-nowrap transition hover:text-blue-600">Company</a>
                <a href="#" class="font-medium whitespace-nowrap transition hover:text-blue-600">Blog</a>
                <a href="#" class="font-medium whitespace-nowrap transition hover:text-blue-600">Contact</a>
            </nav>

            <!-- Desktop Buttons -->
            <div class="hidden space-x-3 md:flex">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Log in
                    </Link>
                    <Link
                        :href="route('register')"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Register
                    </Link>
                </template>
            </div>

            <!-- Mobile Menu Button -->
            <button @click="toggleMobileMenu" class="ml-2 text-2xl md:hidden" aria-label="Toggle Menu">☰</button>
        </div>

        <!-- ✅ Mobile Menu (visible only when toggled) -->
        <div v-if="isMobileMenuOpen" class="md:hidden border-t border-gray-200 dark:border-gray-700 px-4 pb-4">
            <nav class="flex flex-col space-y-2 text-gray-700 dark:text-gray-100">
                <a href="#" class="font-medium transition hover:text-blue-600">Home</a>
                <a href="#" class="font-medium transition hover:text-blue-600">About Us</a>
                <a href="#" class="font-medium transition hover:text-blue-600">Services</a>
                <a href="#" class="font-medium transition hover:text-blue-600">Company</a>
                <a href="#" class="font-medium transition hover:text-blue-600">Blog</a>
                <a href="#" class="font-medium transition hover:text-blue-600">Contact</a>
            </nav>

            <!-- Mobile Auth Buttons -->
            <div class="mt-4 flex flex-col space-y-2">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="rounded border border-[#19140035] px-4 py-2 text-sm text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="rounded border px-4 py-2 text-sm text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Log in
                    </Link>
                    <Link
                        :href="route('register')"
                        class="rounded border border-[#19140035] px-4 py-2 text-sm text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Register
                    </Link>
                </template>
            </div>
        </div>
    </header>


    <!-- ✅ Page Body Below Header -->
    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-0 dark:bg-[#0a0a0a]">
        <!-- Hero Section -->
        <section class="px-4 py-16 text-center sm:px-8 md:px-16 lg:px-20">
            <h1 class="text-3xl leading-tight font-bold text-gray-800 sm:text-4xl md:text-5xl">Committed to People</h1>
            <h2 class="mt-2 text-3xl leading-tight font-bold sm:text-4xl md:text-5xl">Committed <span class="text-blue-600">to the Future</span></h2>
            <p class="mx-auto mt-4 max-w-2xl text-base text-gray-500 sm:text-lg">
                We are <span class="font-semibold text-blue-600">Monst</span>, a Creative Design Social Marketing
            </p>
            <div class="mt-6 flex flex-col items-center justify-center gap-4 sm:flex-row">
                <button class="rounded-md bg-blue-600 px-6 py-3 text-base text-white shadow transition hover:bg-blue-700">Key Features</button>
                <button class="rounded-md bg-blue-100 px-6 py-3 text-base text-blue-700 transition hover:bg-blue-200">How We Work?</button>
            </div>
        </section>

        <div class="relative mx-auto mt-16 mb-8 max-w-6xl px-4">
            <img src="https://wp.alithemes.com/html/monst/assets/imgs/elements/pattern.png" alt="Pattern Background" class="h-auto w-full" />
            <div class="absolute top-[9%] left-1/2 h-auto w-[90%] -translate-x-1/2 transform sm:w-[80%] md:w-[72%]">
                <img src="https://wp.alithemes.com/html/monst/assets/imgs/placeholders/dashboard.png" alt="Dashboard Preview" class="smooth-bounce h-auto w-full" />
            </div>
        </div>
    </div>
</template>


<script setup lang="ts">
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'

const isMobileMenuOpen = ref(false)

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value
}
</script>

