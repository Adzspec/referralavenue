<template>
    <section class="bg-white py-16 px-4 md:px-20">
        <!-- Heading -->
        <div class="text-center mb-10">
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
                        value="support"
                        v-model="form.department"
                        class="form-radio text-blue-600"
                    />
                    <span class="ml-2">Support</span>
                </label>
                <label class="inline-flex items-center">
                    <input
                        type="radio"
                        value="sales"
                        v-model="form.department"
                        class="form-radio text-blue-600"
                    />
                    <span class="ml-2">Sales</span>
                </label>
            </div>
        </div>

        <!-- Form -->
        <form @submit.prevent="submitForm" class="max-w-2xl mx-auto space-y-4">
            <input
                type="text"
                v-model="form.subject"
                placeholder="Subject"
                class="w-full px-4 py-3 bg-slate-100 rounded-md placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />

            <input
                type="text"
                v-model="form.name"
                placeholder="Name"
                class="w-full px-4 py-3 bg-slate-100 rounded-md placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />

            <input
                type="email"
                v-model="form.email"
                placeholder="name@example.com"
                class="w-full px-4 py-3 bg-slate-100 rounded-md placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />

            <textarea
                v-model="form.message"
                placeholder="Message..."
                rows="4"
                class="w-full px-4 py-3 bg-slate-100 rounded-md placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
            ></textarea>

            <!-- File Upload -->
            <div class="flex items-center">
                <label class="bg-blue-900 text-white px-4 py-2 rounded-md cursor-pointer hover:bg-blue-800">
                    Browse
                    <input type="file" @change="handleFile" class="hidden" />
                </label>
                <span v-if="form.file" class="ml-3 text-sm text-gray-600">{{ form.file.name }}</span>
            </div>

            <!-- Terms & Submit -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mt-4">
                <label class="inline-flex items-center text-sm text-gray-700 mb-4 sm:mb-0">
                    <input type="checkbox" v-model="form.agree" class="form-checkbox text-blue-600 mr-2" />
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

<script setup>
import { ref } from "vue";

const form = ref({
    department: "support",
    subject: "",
    name: "",
    email: "",
    message: "",
    file: null,
    agree: false,
});

// File handler
const handleFile = (e) => {
    form.value.file = e.target.files[0];
};

// Submit handler
const submitForm = async () => {
    if (!form.value.agree) {
        alert("You must agree to the terms.");
        return;
    }

    const formData = new FormData();
    for (const key in form.value) {
        formData.append(key, form.value[key]);
    }

    try {
        // Send to Laravel backend (example API route)
        const response = await fetch("/api/contact", {
            method: "POST",
            body: formData,
        });

        if (!response.ok) throw new Error("Submission failed");

        alert("Form submitted successfully!");
        // Reset
        form.value = {
            department: "support",
            subject: "",
            name: "",
            email: "",
            message: "",
            file: null,
            agree: false,
        };
    } catch (error) {
        console.error(error);
        alert("Something went wrong.");
    }
};
</script>
