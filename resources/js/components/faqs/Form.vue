<template>

    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="w-full max-w-5xl p-6 md:p-10">
            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-2xl md:text-4xl font-bold text-gray-800">
                    Get in touch!
                </h2>
                <p class="text-gray-400 mt-2 text-sm md:text-base">
                    We will be glad to hear from you
                </p>
            </div>

            <!-- Contact Info -->
            <div class="flex flex-col md:flex-row justify-center gap-10 text-center mb-8 text-sm">
                <div>
                    <div class="text-blue-600 text-2xl mb-2">📞</div>
                    <p class="text-gray-700 text-md">+48 654-430-309</p>
                    <p class="text-gray-700">+1 6532-430-309</p>
                </div>
                <div>
                    <div class="text-blue-600 text-2xl mb-2">📧</div>
                    <p class="text-gray-700 text-md">contact@monst.com</p>
                    <p class="text-gray-700">pat@example.com</p>
                </div>
                <div>
                    <div class="text-blue-600 text-2xl mb-2">📍</div>
                    <p class="text-gray-700 text-md">11567 E Broadview Dr</p>
                    <p class="text-gray-700">Colorado(CO), 80117</p>
                </div>
            </div>

            <!-- Contact Form -->
            <form class="space-y-6">
                <!-- Department -->
                <div class="flex flex-wrap items-center gap-4 text-sm">
                    <label class="font-medium text-gray-700">Department:</label>
                    <label class="flex items-center space-x-1">
                        <input
                            type="radio"
                            name="department"
                            class="form-radio text-blue-600"
                            checked
                        />
                        <span>Support</span>
                    </label>
                    <label class="flex items-center space-x-1">
                        <input
                            type="radio"
                            name="department"
                            class="form-radio text-blue-600"
                        />
                        <span>Sales</span>
                    </label>
                </div>

                <!-- Two Column Form -->
                <div class="flex justify-center">
                    <div
                        class="grid grid-cols-1 md:grid-cols-[1.2fr,2fr] gap-8 w-full items-center"
                    >
                        <!-- Left Side: Wider inputs -->
                        <div class="space-y-4 w-full">
                            <input
                                type="text"
                                placeholder="Subject"
                                class="w-full p-3.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <input
                                type="text"
                                placeholder="Name"
                                class="w-full p-3.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <input
                                type="email"
                                placeholder="name@example.com"
                                class="w-full p-3.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>

                        <!-- Right Side: Message -->
                        <div class="w-full">
            <textarea
                placeholder="Message..."
                class="w-full p-3.5 border border-gray-300 rounded-md text-sm resize-none min-h-[180px] focus:outline-none focus:ring-2 focus:ring-blue-500"
            ></textarea>
                        </div>
                    </div>
                </div>

                <!-- Terms and Submit -->
                <div
                    class="flex flex-col md:flex-row items-center justify-between gap-4 text-sm"
                >
                    <label class="flex items-center space-x-2 text-gray-600">
                        <input type="checkbox" class="form-checkbox text-blue-600" />
                        <span>I agree to terms and conditions.</span>
                    </label>
                    <div>
                        <button
                            class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition text-sm"
                        >
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
