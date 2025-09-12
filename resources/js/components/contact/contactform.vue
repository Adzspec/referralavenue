<template>
    <section class="bg-white px-4 py-16 md:px-20">
        <!-- Heading -->
        <div class="mb-10 text-center">
            <h2 class="text-3xl font-bold text-gray-800 md:text-4xl">
                We will <span class="text-blue-600">be glad</span> to hear <br />
                from you!
            </h2>
        </div>

        <!-- Radio Options -->
        <div class="mb-6 flex items-center justify-center gap-4 text-center">
            <p class="font-semibold text-gray-700">Department:</p>
            <div class="flex justify-center gap-6 text-gray-600">
                <label class="inline-flex items-center">
                    <input type="radio" value="support" v-model="form.department" class="form-radio text-blue-600" />
                    <span class="ml-2">Support</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" value="sales" v-model="form.department" class="form-radio text-blue-600" />
                    <span class="ml-2">Sales</span>
                </label>
            </div>
        </div>

        <!-- Form -->
        <form @submit.prevent="submitForm" class="mx-auto max-w-2xl space-y-4" enctype="multipart/form-data">
            <input
                type="text"
                v-model="form.subject"
                placeholder="Subject"
                required
                class="w-full rounded-md bg-slate-100 px-4 py-3 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
            />
            <p v-if="errors.subject" class="text-sm text-red-600">{{ errors.subject }}</p>

            <input
                type="text"
                v-model="form.name"
                placeholder="Name"
                required
                class="w-full rounded-md bg-slate-100 px-4 py-3 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
            />
            <p v-if="errors.name" class="text-sm text-red-600">{{ errors.name }}</p>

            <input
                type="email"
                v-model="form.email"
                required
                placeholder="name@example.com"
                class="w-full rounded-md bg-slate-100 px-4 py-3 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
            />
            <p v-if="errors.email" class="text-sm text-red-600">{{ errors.email }}</p>

            <textarea
                v-model="form.message"
                placeholder="Message..."
                rows="4"
                required
                class="w-full rounded-md bg-slate-100 px-4 py-3 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
            ></textarea>
            <p v-if="errors.message" class="text-sm text-red-600">{{ errors.message }}</p>

            <!-- File Upload -->
            <div class="flex items-center">
                <label class="cursor-pointer rounded-md bg-blue-900 px-4 py-2 text-white hover:bg-blue-800">
                    Browse
                    <input type="file" @change="handleFile" class="hidden" />
                </label>
                <span v-if="fileName" class="ml-3 text-sm text-gray-600">{{ fileName }}</span>
            </div>
            <p v-if="errors.file" class="text-sm text-red-600">{{ errors.file }}</p>

            <!-- Terms & Submit -->
            <div class="mt-4 flex flex-col items-start justify-between sm:flex-row sm:items-center">
                <label class="mb-4 inline-flex items-center text-sm text-gray-700 sm:mb-0">
                    <input type="checkbox" v-model="form.agree" class="form-checkbox mr-2 text-blue-600" />
                    I agree to terms and conditions.
                </label>
                <button
                    type="submit"
                    :disabled="processing || !form.agree"
                    class="rounded-md bg-blue-600 px-6 py-2 text-white transition hover:bg-blue-700 disabled:opacity-60"
                >
                    <span v-if="!processing">Submit</span>
                    <span v-else>Submitting…</span>
                </button>
            </div>
        </form>
    </section>
</template>

<script lang="ts" setup>
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useMessage } from 'naive-ui';

type Dept = 'support' | 'sales';
type ContactForm = {
    department: Dept;
    subject: string;
    name: string;
    email: string;
    message: string;
    file: File | null;
    agree: boolean;
};

const form = useForm<ContactForm>({
    department: 'support',
    subject: '',
    name: '',
    email: '',
    message: '',
    file: null,
    agree: false,
});

const processing = computed(() => form.processing);
const errors = form.errors as Record<string, string>;
const fileName = computed(() => (form.file ? form.file.name : ''));
const message = useMessage();

function handleFile(e: Event) {
    const target = e.target as HTMLInputElement;

    // ✅ No setData needed; assign directly
    form.file = target.files?.[0] ?? null;
}

function submitForm() {
    form.post(route?.('contact.store') ?? '/contact', {
        forceFormData: true, // ensure multipart/form-data for file
        onSuccess: () => {
            form.reset('subject', 'name', 'email', 'message', 'file', 'agree');
            message.success('Form submitted successfully!');
        },
    });
}
</script>
