<template>
    <Head title="Welcome">
    </Head>
    <Header/>
    <HeroSection/>
    <Counting/>
    <KeyFeature/>
   <Cards/>
    <Slider/>
    <startup-section/>
    <signup/>

</template>


<script setup lang="ts">
// import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import Header from '@/components/common/header.vue'
import HeroSection from '@/components/home/HeroSection.vue';
import Counting from '@/components/home/Counting.vue';
import KeyFeature from '@/components/home/KeyFeature.vue';
import Cards from '@/components/home/Cards.vue';
import Slider from '@/components/home/Slider.vue';
import StartupSection from '@/components/home/StartupSection.vue';
import Signup from '@/components/home/Signup.vue';

</script>

