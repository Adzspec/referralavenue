<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
// import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
// import { Input } from '@/components/ui/input';
// import { Label } from '@/components/ui/label';
// import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
// import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
<!--    <AuthBase title="Log in to your account" description="Enter your email and password below to log in">-->
        <Head title="Log in" />

<!--        <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">-->
<!--            {{ status }}-->
<!--        </div>-->

<!--        <form @submit.prevent="submit" class="flex flex-col gap-6">-->
<!--            <div class="grid gap-6">-->
<!--                <div class="grid gap-2">-->
<!--                    <Label for="email">Email address</Label>-->
<!--                    <Input-->
<!--                        id="email"-->
<!--                        type="email"-->
<!--                        required-->
<!--                        autofocus-->
<!--                        :tabindex="1"-->
<!--                        autocomplete="email"-->
<!--                        v-model="form.email"-->
<!--                        placeholder="email@example.com"-->
<!--                    />-->
<!--                    <InputError :message="form.errors.email" />-->
<!--                </div>-->

<!--                <div class="grid gap-2">-->
<!--                    <div class="flex items-center justify-between">-->
<!--                        <Label for="password">Password</Label>-->
<!--                        <TextLink v-if="canResetPassword" :href="route('password.request')" class="text-sm" :tabindex="5">-->
<!--                            Forgot password?-->
<!--                        </TextLink>-->
<!--                    </div>-->
<!--                    <Input-->
<!--                        id="password"-->
<!--                        type="password"-->
<!--                        required-->
<!--                        :tabindex="2"-->
<!--                        autocomplete="current-password"-->
<!--                        v-model="form.password"-->
<!--                        placeholder="Password"-->
<!--                    />-->
<!--                    <InputError :message="form.errors.password" />-->
<!--                </div>-->

<!--                <div class="flex items-center justify-between">-->
<!--                    <Label for="remember" class="flex items-center space-x-3">-->
<!--                        <Checkbox id="remember" v-model="form.remember" :tabindex="3" />-->
<!--                        <span>Remember me</span>-->
<!--                    </Label>-->
<!--                </div>-->

<!--                <Button type="submit" class="mt-4 w-full" :tabindex="4" :disabled="form.processing">-->
<!--                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />-->
<!--                    Log in-->
<!--                </Button>-->
<!--            </div>-->

<!--            <div class="text-center text-sm text-muted-foreground">-->
<!--                Don't have an account?-->
<!--                <TextLink :href="route('register')" :tabindex="5">Sign up</TextLink>-->
<!--            </div>-->
<!--        </form>-->
<!--    </AuthBase>-->
    <section class="min-h-screen flex items-center justify-center">
        <div class="bg-white/30 backdrop-blur-md p-8 rounded-xl max-w-md w-full text-white shadow-2xl border border-white/40">
            <h2 class="text-2xl font-semibold text-center mb-6">Log in to your account</h2>

            <!-- Form -->
            <form @submit.prevent="submit"  class="space-y-4">
                <input
                    id="email"
                    type="email"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="email"
                    v-model="form.email"
                    placeholder="email@example.com"
                    class="w-full px-4 py-2 rounded-full bg-white/20 border border-white/50 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white"
                />
                <InputError :message="form.errors.email" />
                <div class="relative">
                    <input
                        id="password"
                        type="password"
                        required
                        :tabindex="2"
                        v-model="form.password"
                        placeholder="Password"
                        class="w-full px-4 py-2 rounded-full bg-white/20 border border-white/50 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <button
                    type="submit"
                    class="w-full py-2 bg-slate-700 text-white font-semibold rounded-full hover:bg-slate-600 transition"
                >
                    SIGN IN
                </button>

                <div class="flex justify-between text-sm mt-2">
                    <label class="flex items-center">
                        <Checkbox id="remember" v-model="form.remember" :tabindex="3" />
                        <span class="pl-1">Remember me</span>
                    </label>
                    <TextLink v-if="canResetPassword" :href="route('password.request')" class="text-sm" :tabindex="5">
                        Forgot password?
                    </TextLink>
<!--                    <a href="#" class="hover:underline">Forgot Password</a>-->
                </div>
            </form>
            <div class="my-6 text-center text-sm text-white/80">
                Don't have an account?
                <TextLink :href="route('register')" :tabindex="5">Sign up</TextLink>
            </div>
<!--            <div class="my-6 text-center text-white/80">— Or Sign In With —</div>-->

<!--            <div class="flex justify-center gap-4">-->
<!--                <button class="bg-white text-black font-medium px-6 py-2 rounded hover:bg-gray-100 transition">-->
<!--                    Facebook-->
<!--                </button>-->
<!--                <button class="bg-white text-black font-medium px-6 py-2 rounded hover:bg-gray-100 transition">-->
<!--                    Twitter-->
<!--                </button>-->
<!--            </div>-->
        </div>
    </section>
</template>
<style>
body {
    background-image: url('https://images.unsplash.com/photo-1515263487990-61b07816b324?auto=format&fit=crop&w=1920&q=80');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}
</style>

