<template>
    <section class="bg-white py-16 px-4 md:px-20">
        <!-- Heading -->
        <div class="text-center mb-10">
            <button
                class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-xs mb-4"
            >
                Contact Us
            </button>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800">
                We will <span class="text-blue-600">be glad</span> to hear <br />
                from you!
            </h2>
        </div>

        <!-- Radio Options -->
        <div class="text-center mb-6 flex items-center justify-center gap-4">
            <p class="text-gray-700 font-semibold">Department:</p>
            <div class="flex justify-center gap-6 text-gray-600">
                <label class="inline-flex items-center">
                    <input
                        type="radio"
                        name="department"
                        value="support"
                        class="form-radio text-blue-600"
                        checked
                    />
                    <span class="ml-2">Support</span>
                </label>
                <label class="inline-flex items-center">
                    <input
                        type="radio"
                        name="department"
                        value="sales"
                        class="form-radio text-blue-600"
                    />
                    <span class="ml-2">Sales</span>
                </label>
            </div>
        </div>

        <!-- Form -->
        <form class="max-w-2xl mx-auto space-y-4">
            <input
                type="text"
                placeholder="Subject"
                class="w-full px-4 py-3 bg-slate-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />

            <input
                type="text"
                placeholder="Name"
                class="w-full px-4 py-3 bg-slate-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />

            <input
                type="email"
                placeholder="name@example.com"
                class="w-full px-4 py-3 bg-slate-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />

            <textarea
                placeholder="Message..."
                rows="4"
                class="w-full px-4 py-3 bg-slate-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            ></textarea>

            <!-- File Upload -->
            <div class="flex items-center">
                <label
                    class="bg-blue-900 text-white px-4 py-2 rounded-md cursor-pointer hover:bg-blue-800"
                >
                    Browse
                    <input type="file" class="hidden" />
                </label>
            </div>

            <!-- Terms & Submit -->
            <div
                class="flex flex-col sm:flex-row justify-between items-start sm:items-center mt-4"
            >
                <label
                    class="inline-flex items-center text-sm text-gray-700 mb-4 sm:mb-0"
                >
                    <input type="checkbox" class="form-checkbox text-blue-600 mr-2" />
                    I agree to terms and conditions.
                </label>
                <button
                    type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition"
                >
                    Submit
                </button>
            </div>
        </form>
    </section>

</template>
<script setup lang="ts">
</script>
