<template>
    <section class="bg-green-100 py-12">
        <!-- Top -->
        <div class="relative mx-auto max-w-7xl rounded-xl px-4 py-12">
            <div class="mb-10 text-center">
                <p class="text-sm tracking-widest text-gray-400 uppercase">Testimonial</p>
                <h2 class="text-3xl font-bold text-gray-800 md:text-4xl">What Our Users Say</h2>
            </div>

            <!-- Slider Wrapper -->
            <div class="relative">
                <div class="swiper pb-12">
                    <div class="swiper-wrapper">
                        <div
                            v-for="(t, index) in testimonials"
                            :key="index"
                            class="swiper-slide relative flex min-h-[250px] flex-col justify-between rounded-xl border border-gray-200 bg-white p-6 shadow"
                        >
                            <div class="mb-4 text-4xl" :class="index % 2 === 0 ? 'text-yellow-500' : 'text-sky-500'">“</div>
                            <p class="mb-6 text-gray-600">{{ t.message }}</p>
                            <div class="mt-auto flex items-center">
                                <img :src="t.image" class="mr-3 h-10 w-10 rounded-full object-cover" />
                                <div>
                                    <p class="font-bold text-gray-900">{{ t.name }}</p>
                                    <p class="text-sm text-gray-500">{{ t.location }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation arrows -->
                <div class="swiper-button-prev absolute top-1/2 left-2 flex -translate-y-1/2 md:!left-[-2.5rem]"></div>
                <div class="swiper-button-next absolute top-1/2 right-2 flex -translate-y-1/2 md:!right-[-2.5rem]"></div>

                <!-- Custom Pagination -->
                <div class="mt-4 flex justify-center gap-2">
                    <button
                        v-for="n in totalGroups"
                        :key="n"
                        @click="goToGroup(n - 1)"
                        :class="['h-3 w-3 rounded-full transition-colors duration-300', currentGroup === n - 1 ? 'bg-gray-800' : 'bg-gray-700']"
                    ></button>
                </div>
            </div>
        </div>
    </section>
</template>

<script lang="ts">
import Swiper from 'swiper/bundle';
import 'swiper/css';
import 'swiper/css/navigation';
import { defineComponent } from 'vue';

export default defineComponent({
    name: 'TestimonialSlider',
    data() {
        return {
            swiperInstance: null as Swiper | null,  // ✅ FIXED
            currentGroup: 0,
            testimonials: [
                {
                    message:
                        'This platform helped us launch a fully branded affiliate site in a single day. The publisher tools and reporting are game-changers.',
                    image: 'https://cdn-icons-png.flaticon.com/512/149/149071.png',
                    name: 'Cartero Digital Marketing',
                    location: 'Marketing Lead',
                },
                {
                    message: 'We cut onboarding time by 80% and doubled active publishers in the first month.',
                    image: 'https://cdn-icons-png.flaticon.com/512/149/149071.png',
                    name: 'Lunar media',
                    location: 'Head of Growth',
                },
                {
                    message:
                        'This platform helped us launch a fully branded affiliate site in a single day. The publisher tools and reporting are game-changers.',
                    image: 'https://cdn-icons-png.flaticon.com/512/149/149071.png',
                    name: 'Cartero Digital Marketing',
                    location: 'Marketing Lead',
                },
            ],
        };
    },
    computed: {
        totalGroups(): number {
            return Math.ceil(this.testimonials.length / 3);
        },
    },
    mounted() {
        this.swiperInstance = new Swiper('.swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: false,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                640: { slidesPerView: 1 },
                768: { slidesPerView: 2 },
                1024: { slidesPerView: 3 },
            },
            on: {
                slideChange: () => {
                    if (this.swiperInstance) {
                        const index = this.swiperInstance.activeIndex;
                        this.currentGroup = Math.floor(index / 3);
                    }
                },
            },
        });
    },
    methods: {
        goToGroup(groupIndex: number) {
            if (this.swiperInstance) {
                this.swiperInstance.slideTo(groupIndex * 3);
            }
        },
    },
});
</script>


<style scoped>
::v-deep(.swiper-button-prev),
::v-deep(.swiper-button-next) {
    color: #1f2937; /* Tailwind gray-800 */
}
</style>

<style>
@keyframes smoothBounce {
    0%,
    100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-12px);
    }
}

.smooth-bounce {
    animation: smoothBounce 2.5s ease-in-out infinite;
}
</style>
