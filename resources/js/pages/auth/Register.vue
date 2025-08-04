<template>
    <section class="flex min-h-[100vh] items-center justify-center bg-gradient-to-br from-gray-400 via-white to-gray-100 px-4 py-8 font-sans">
        <div class="flex w-full max-w-5xl flex-col overflow-hidden rounded-3xl bg-white shadow-xl md:flex-row">
            <!-- Left Panel -->
            <div
                class="relative bg-cover bg-center md:w-1/2"
                style="background-image: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1000&q=80')"
            >
                <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                <div class="relative z-10 flex h-full flex-col justify-center space-y-4 p-8 text-white">
                    <h2 class="text-3xl leading-snug font-extrabold">Welcome to Referralavenue</h2>
                    <p class="text-base text-white/90">Connect, grow, and access exclusive content.</p>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center gap-2">✅ Access premium content</li>
                        <li class="flex items-center gap-2">✅ Weekly expert tips</li>
                        <li class="flex items-center gap-2">✅ Priority support</li>
                    </ul>
                </div>
            </div>

            <!-- Right Form -->
            <div class="w-full p-6 sm:p-8 md:w-1/2">
                <h2 class="mb-4 text-center text-2xl font-bold text-gray-800">Create your account</h2>
                <form @submit.prevent="submit" class="space-y-4 text-sm">
                    <div>
                        <label for="name" class="mb-1 block font-medium text-gray-700">Company Name</label>
                        <input
                            type="text"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="name"
                            v-model="form.name"
                            id="name"
                            placeholder="Company name"
                            class="w-full rounded-lg border border-gray-300 p-2.5 focus:ring-2 focus:ring-blue-400 focus:outline-none text-black"
                        />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div>
                        <label for="email" class="mb-1 block font-medium text-gray-700">Company Email</label>
                        <input
                            type="email"
                            id="email"
                            required
                            :tabindex="2"
                            autocomplete="email"
                            v-model="form.email"
                            placeholder="you@example.com"
                            class="w-full rounded-lg border border-gray-300 p-2.5 focus:ring-2 focus:ring-blue-400 focus:outline-none text-black"
                        />
                        <InputError :message="form.errors.email" />
                        <div class="mt-1 text-xs flex items-center gap-2">
                            <span v-if="emailValid" class="text-green-600">✔</span>
                            <span v-else class="text-gray-400">✖</span>
                            <span :class="emailValid ? 'text-green-700' : 'text-gray-500'">Valid email address</span>
                        </div>
                    </div>

                    <div class="relative">
                        <label for="password" class="mb-1 block font-medium text-gray-700">Password</label>
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            id="password"
                            required
                            :tabindex="4"
                            v-model="form.password"
                            placeholder="********"
                            class="w-full rounded-lg border border-gray-300 p-2.5 pr-10 focus:ring-2 focus:ring-blue-400 focus:outline-none text-black"
                        />
                        <button
                            type="button"
                            class="absolute right-2 top-[33px] z-20 p-1"
                            @click="showPassword = !showPassword"
                            tabindex="-1"
                        >
                            <Eye v-if="!showPassword" class="w-5 h-5 text-gray-400" />
                            <EyeOff v-else class="w-5 h-5 text-gray-400" />
                        </button>
                        <InputError :message="form.errors.password" />
                        <ul class="mt-2 space-y-1 text-xs">
                            <li v-for="rule in passwordRules" :key="rule.label" class="flex items-center gap-2">
                                <span v-if="rule.valid" class="text-green-600">✔</span>
                                <span v-else class="text-gray-400">✖</span>
                                <span :class="rule.valid ? 'text-green-700' : 'text-gray-500'">
                                    {{ rule.label }}
                                </span>
                            </li>
                        </ul>
                    </div>

                    <div class="relative">
                        <label for="confirm_password" class="mb-1 block font-medium text-gray-700">Confirm Password</label>
                        <input
                            :type="showConfirmPassword ? 'text' : 'password'"
                            id="confirm_password"
                            required
                            :tabindex="5"
                            v-model="form.password_confirmation"
                            placeholder="********"
                            class="w-full rounded-lg border border-gray-300 p-2.5 pr-10 focus:ring-2 focus:ring-blue-400 focus:outline-none text-black"
                        />
                        <button
                            type="button"
                            class="absolute right-2 top-[33px] z-20 p-1"
                            @click="showConfirmPassword = !showConfirmPassword"
                            tabindex="-1"
                        >
                            <Eye v-if="!showConfirmPassword" class="w-5 h-5 text-gray-400" />
                            <EyeOff v-else class="w-5 h-5 text-gray-400" />
                        </button>
                        <InputError :message="form.errors.password_confirmation" />
                        <div class="mt-1 text-xs flex items-center gap-2">
                            <span v-if="passwordConfirmValid" class="text-green-600">✔</span>
                            <span v-else class="text-gray-400">✖</span>
                            <span :class="passwordConfirmValid ? 'text-green-700' : 'text-gray-500'">Passwords match</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="terms" class="accent-blue-500" v-model="acceptedTerms" />
                        <label for="terms" class="text-xs text-gray-600"
                        >I agree to the <a href="#" class="text-blue-600 hover:underline">terms and policy</a></label
                        >
                    </div>

                    <Button type="submit" class="w-full rounded-lg bg-blue-600 py-2.5 font-medium text-white transition hover:bg-blue-700" tabindex="5" :disabled="!canSubmit">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Create account
                    </Button>

                    <p class="mt-4 text-center text-xs text-gray-600">
                        Already have an account?
                        <a :href="route('login')" class="text-blue-600 hover:underline">Login</a>
                    </p>
                </form>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { useForm } from '@inertiajs/vue3';
import { LoaderCircle, Eye, EyeOff } from 'lucide-vue-next';
import { computed, ref } from 'vue';

const acceptedTerms = ref(false);
const showPassword = ref(false);
const showConfirmPassword = ref(false);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const emailValid = computed(() =>
    /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)
);

const passwordRules = computed(() => [
    { label: 'At least 8 characters', valid: form.password.length >= 8 },
    { label: 'At least one uppercase letter', valid: /[A-Z]/.test(form.password) },
    { label: 'At least one number', valid: /\d/.test(form.password) },
    { label: 'At least one special character', valid: /[!@#$%^&*(),.?":{}|<>]/.test(form.password) },
]);

const passwordConfirmValid = computed(() => {
    return (
        form.password_confirmation.length > 0 &&
        form.password_confirmation === form.password
    );
});

const canSubmit = computed(() =>
    acceptedTerms.value &&
    emailValid.value &&
    passwordRules.value.every(rule => rule.valid) &&
    passwordConfirmValid.value &&
    !form.processing
);

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>
