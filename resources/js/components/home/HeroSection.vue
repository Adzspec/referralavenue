<template>
    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-0 dark:bg-[#0a0a0a]">
        <!-- Hero Section -->
        <section class="px-4 py-16 text-center sm:px-8 md:px-16 lg:px-20">
            <h1 class="text-3xl leading-tight font-bold text-gray-800 sm:text-4xl md:text-5xl">Launch, Manage, and Grow </h1>
            <h2 class="mt-2 text-3xl leading-tight font-bold sm:text-4xl md:text-5xl">Your Affiliate Business <span class="text-blue-600">All in One Place</span></h2>
            <p class="mx-auto mt-4 max-w-2xl text-base text-gray-500 sm:text-lg">
                We provide <span class="font-semibold text-blue-600">everything</span> you need to build and run a fully branded, high-performing affiliate site—without the need for developers or complex integrations
            </p>
            <div class="mt-6 flex flex-col items-center justify-center gap-4 sm:flex-row">
                <a :href="route('key-features')"><button class="cursor-pointer rounded-md bg-blue-600 px-6 py-3 text-base text-white shadow transition hover:bg-blue-700">Key Features</button></a>
                <a :href="route('key-features')"><button class="cursor-pointer rounded-md bg-blue-100 px-6 py-3 text-base text-blue-700 transition hover:bg-blue-200">How We Work?</button></a>
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
</script>
