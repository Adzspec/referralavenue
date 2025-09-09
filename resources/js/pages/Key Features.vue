<template>
    <Header/>
    <HeaderSection/>
<!--    <WebServices/>-->
    <CountUp/>

    <ContactUs/>
    <SignUp/>
    <Footer/>

</template>
<script setup lang="ts">
import Header from '@/components/common/header.vue';
import HeaderSection from '@/components/Key Features/HeaderSection.vue';
import WebServices from '@/components/Key Features/WebServices.vue';
import CountUp from '@/components/Key Features/CountUp.vue';

import ContactUs from '@/components/Key Features/ContactUs.vue';
import SignUp from '@/components/Key Features/SignUp.vue';
import Footer from '@/components/common/Footer.vue';
</script>
