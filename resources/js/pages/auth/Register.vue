<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthBase title="Create an account" description="Enter your details below to create your account">
        <Head title="Register" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name" v-model="form.name" placeholder="Full name" />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email address</Label>
                    <Input id="email" type="email" required :tabindex="2" autocomplete="email" v-model="form.email" placeholder="email@example.com" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        v-model="form.password"
                        placeholder="Password"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirm password</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        v-model="form.password_confirmation"
                        placeholder="Confirm password"
                    />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Create account
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                Already have an account?
                <TextLink :href="route('login')" class="underline underline-offset-4" :tabindex="6">Log in</TextLink>
            </div>
        </form>
    </AuthBase>

    <section class=" bg-gradient-to-br from-gray-400 via-white to-gray-100 font-sans min-h-[80vh] flex items-center justify-center px-4 py-8">
        <div class="bg-white w-full max-w-5xl rounded-3xl shadow-xl overflow-hidden flex flex-col md:flex-row">

            <!-- Left Panel -->
            <div class="md:w-1/2 relative bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1000&q=80');">
                <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                <div class="relative z-10 h-full flex flex-col justify-center p-8 text-white space-y-4">
                    <h2 class="text-3xl font-extrabold leading-snug">Welcome to Our Community</h2>
                    <p class="text-base text-white/90">Connect, grow, and access exclusive content.</p>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center gap-2">
                            ✅ Access premium content
                        </li>
                        <li class="flex items-center gap-2">
                            ✅ Weekly expert tips
                        </li>
                        <li class="flex items-center gap-2">
                            ✅ Priority support
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Right Form -->
            <div class="w-full md:w-1/2 p-6 sm:p-8">
                <h2 class="text-2xl font-bold text-gray-800 text-center mb-4">Create your account</h2>
                <form class="space-y-4 text-sm">
                    <div>
                        <label for="name" class="block font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" id="name" placeholder="Your name" class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                    </div>

                    <div>
                        <label for="email" class="block font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" placeholder="you@example.com" class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                    </div>

                    <div>
                        <label for="password" class="block font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" id="password" placeholder="********" class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                    </div>

                    <div>
                        <label for="confirm_password" class="block font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input type="password" id="confirm_password" placeholder="********" class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="terms" class="accent-blue-500" />
                        <label for="terms" class="text-xs text-gray-600">I agree to the <a href="#" class="text-blue-600 hover:underline">terms and policy</a></label>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition">
                        Sign Up
                    </button>

                    <div class="flex items-center my-3">
                        <hr class="flex-grow border-t border-gray-300" />
                        <span class="px-2 text-gray-500 text-xs">or</span>
                        <hr class="flex-grow border-t border-gray-300" />
                    </div>

                    <button class="w-full flex items-center justify-center gap-2 border border-gray-300 py-2 rounded-lg hover:bg-gray-100 transition">
                        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" class="w-4 h-4" />
                        <span class="text-xs font-medium text-gray-700">Sign up with Google</span>
                    </button>

                    <p class="text-center text-xs text-gray-600 mt-4">
                        Already have an account?
                        <a href="#" class="text-blue-600 hover:underline">Sign in</a>
                    </p>
                </form>
            </div>
        </div>
    </section></template>
