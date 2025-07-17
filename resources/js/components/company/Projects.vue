<template>
    <div class=" p-4">
        <section class="bg-white py-16 px-4 text-center">
        <span
            class="inline-block bg-blue-100 text-blue-600 text-sm font-medium px-4 py-1 rounded-full mb-4"
        >Our Projects</span
        >
            <h2 class="text-2xl md:text-4xl font-bold text-gray-800">
                Check Out <br />
                <span class="text-blue-600">Our Latest Project</span>
            </h2>
            <p class="mt-4 text-gray-400 text-md md:text-base max-w-xl mx-auto">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed luctus
                eget justo et iaculis.
            </p>
        </section>
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-4 items-stretch">
            <!-- Left: 2x2 Grid of Images (spans 6/12 columns) -->
            <div class="lg:col-span-6">
                <div class="grid grid-cols-2 gap-4">
                    <!-- gap-4 adds 16px spacing -->
                    <!-- All images -->
                    <img
                        src="https://wp.alithemes.com/html/monst/assets/imgs/placeholders/img-10.jpg"
                        class="w-full h-[210px] object-cover rounded-lg shadow"
                        alt="img1"
                    />
                    <img
                        src="https://wp.alithemes.com/html/monst/assets/imgs/placeholders/img-11.jpg"
                        class="w-full h-[210px] object-cover rounded-lg shadow"
                        alt="img2"
                    />
                    <img
                        src="https://wp.alithemes.com/html/monst/assets/imgs/placeholders/img-12.jpg"
                        class="w-full h-[210px] object-cover rounded-lg shadow"
                        alt="img3"
                    />
                    <img
                        src="https://wp.alithemes.com/html/monst/assets/imgs/placeholders/img-13.jpg"
                        class="w-full h-[210px] object-cover rounded-lg shadow"
                        alt="img4"
                    />
                </div>
            </div>

            <!-- Right: Single Image with overlay card -->
            <div
                class="relative w-full lg:col-span-6 rounded-lg overflow-hidden shadow h-[436px]"
            >
                <!-- 210x2 + 16 = 436px -->
                <img
                    src="https://wp.alithemes.com/html/monst/assets/imgs/placeholders/img-9.jpg"
                    class="w-full h-full object-cover"
                    alt="main"
                />
                <div class="absolute inset-0 flex items-center justify-center">
                    <div
                        class="bg-white bg-opacity-90 p-6 rounded-lg text-center max-w-md shadow-lg mx-4"
                    >
                        <h2 class="text-2xl font-semibold mb-2">Web Design</h2>
                        <p class="text-gray-600 text-sm mb-4">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi
                            eveniet rem fugiat deserunt molestias.
                        </p>
                        <button
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded"
                        >
                            View details
                        </button>
                    </div>
                </div>
            </div>

            <div
                class="relative w-full lg:col-span-6 rounded-lg overflow-hidden shadow h-[436px]"
            >
                <!-- 210x2 + 16 = 436px -->
                <img
                    src="https://wp.alithemes.com/html/monst/assets/imgs/placeholders/img-9.jpg"
                    class="w-full h-full object-cover"
                    alt="main"
                />
                <div class="absolute inset-0 flex items-center justify-center">
                    <div
                        class="bg-white bg-opacity-90 p-6 rounded-lg text-center max-w-md shadow-lg mx-4"
                    >
                        <h2 class="text-2xl font-semibold mb-2">Web Design</h2>
                        <p class="text-gray-600 text-sm mb-4">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi
                            eveniet rem fugiat deserunt molestias.
                        </p>
                        <button
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded"
                        >
                            View details
                        </button>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-6">
                <div class="grid grid-cols-2 gap-4">
                    <!-- gap-4 adds 16px spacing -->
                    <!-- All images -->
                    <img
                        src="https://wp.alithemes.com/html/monst/assets/imgs/placeholders/img-10.jpg"
                        class="w-full h-[210px] object-cover rounded-lg shadow"
                        alt="img1"
                    />
                    <img
                        src="https://wp.alithemes.com/html/monst/assets/imgs/placeholders/img-11.jpg"
                        class="w-full h-[210px] object-cover rounded-lg shadow"
                        alt="img2"
                    />
                    <img
                        src="https://wp.alithemes.com/html/monst/assets/imgs/placeholders/img-12.jpg"
                        class="w-full h-[210px] object-cover rounded-lg shadow"
                        alt="img3"
                    />
                    <img
                        src="https://wp.alithemes.com/html/monst/assets/imgs/placeholders/img-13.jpg"
                        class="w-full h-[210px] object-cover rounded-lg shadow"
                        alt="img4"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
