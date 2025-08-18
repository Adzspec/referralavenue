<template>
    <Header/>
    <HeaderSection/>
    <WebServices/>
    <CountUp/>

    <ContactUs/>
    <SignUp/>
    <Footer/>

</template>
<script setup lang="ts">
import Header from '@/components/common/header.vue';
import HeaderSection from '@/components/services/HeaderSection.vue';
import WebServices from '@/components/services/WebServices.vue';
import CountUp from '@/components/services/CountUp.vue';

import ContactUs from '@/components/services/ContactUs.vue';
import SignUp from '@/components/services/SignUp.vue';
import Footer from '@/components/common/Footer.vue';
</script>
