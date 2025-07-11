<template>
    <div class="flex flex-col md:flex-row gap-10 p-6 md:p-8 items-start max-w-7xl mx-auto">
        <!-- Left Text -->
        <div class="w-full md:w-1/3">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold leading-tight mb-4">
                Simple<br />
                Solution for <br />
                <span class="text-blue-600">Complex</span><br />
                Connections
            </h2>
            <p class="text-gray-600 text-base sm:text-lg mb-6 leading-relaxed">
                Lorem ipsum dolor sit amet,<br />
                consectetur adipiscing elit.<br />
                Sed luctus eget justo et iaculis.
            </p>
        </div>

        <!-- Right Slider -->
        <div class="w-full md:w-2/3">
            <Swiper
                :modules="[Navigation]"
                :slides-per-view="2"
                :space-between="20"
                :navigation="true"
                :breakpoints="{
          0: { slidesPerView: 1 },
          768: { slidesPerView: 2 }
        }"
                class="mySwiper"
            >
                <SwiperSlide v-for="(slide, index) in slides" :key="index">
                    <div class="bg-white p-4 border border-gray-200 rounded-xl">
                        <img
                            :src="slide.img"
                            :alt="slide.title"
                            class="rounded-lg mb-4 w-full object-cover h-64 md:h-52"
                        />
                        <h3 class="text-lg font-semibold">{{ slide.title }}</h3>
                        <p class="text-sm text-gray-500">{{ slide.subtitle }}</p>
                        <a href="#" class="text-blue-600 text-sm">View Details</a>
                    </div>
                </SwiperSlide>
            </Swiper>
        </div>
    </div>
</template>

<script setup lang="ts">
import 'swiper/css'
import 'swiper/css/navigation'

import { Swiper, SwiperSlide } from 'swiper/vue'
import { Navigation } from 'swiper/modules'

const slides = [
    {
        img: 'https://wp.alithemes.com/html/monst/assets/imgs/placeholders/img-4.jpg',
        title: 'User Growth',
        subtitle: 'Harvard University',
    },
    {
        img: 'https://wp.alithemes.com/html/monst/assets/imgs/placeholders/img-2.jpg',
        title: 'Product Launch',
        subtitle: 'CocaCola, Co',
    },
    {
        img: 'https://wp.alithemes.com/html/monst/assets/imgs/placeholders/img-3.jpg',
        title: 'Product Launch',
        subtitle: 'CocaCola, Co',
    },
    {
        img: 'https://wp.alithemes.com/html/monst/assets/imgs/placeholders/img-5.jpg',
        title: 'Analytics',
        subtitle: 'Google Inc',
    },
]
</script>

<style scoped>
/* Optional: style navigation buttons if needed */
.swiper-button-next,
.swiper-button-prev {
    color: #2563eb; /* Tailwind blue-600 */
}
</style>
