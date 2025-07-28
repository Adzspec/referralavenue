<template>
    <Head title="Welcome">
    </Head>
    <Header/>
    <HeroSection/>
    <Counting/>
    <KeyFeature/>
   <Cards/>
    <Slider/>
    <Pricing :plans="plans" :user="user" />
    <Signup/>
    <Footer/>

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
import Pricing from '@/components/home/Pricing.vue';
import Signup from '@/components/home/Signup.vue';
import Footer from '@/components/common/Footer.vue';

defineProps<{
    plans: {
        id: number | string
        name: string
        price: number | string
        currency: string
        features: string[]
    }[],
    user:any,
}>()
</script>

