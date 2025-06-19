<script setup lang="ts">
// import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
// import { useMessage } from 'naive-ui';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import EditUserModal from '@/components/users/EditUserModal.vue';

const props = defineProps<{
    user: {
        id: number;
        name: string;
        email: string;
    };
}>();

const form = useForm({
    name: props.user.name,
    email: props.user.email,
});

// const message = useMessage();

function updateUser() {
    form.put(route('users.update', props.user.id), {
        preserveScroll: true,
        // onSuccess: () => {
        //     message.success('User updated successfully!');
        // },
        // onError: () => {
        //     message.error('Failed to update user. Please check the form.');
        // }
    });
}
</script>

<template>
    <AppLayout>
        <div class="p-4 max-w-2xl mx-auto">
            <h1 class="text-2xl font-bold mb-6">Edit User</h1>

            <n-form @submit.prevent="updateUser" :model="form" :rules="{
                name: { required: true, message: 'Name is required', trigger: 'blur' },
                email: { required: true, message: 'Email is required', trigger: 'blur' }
            }" label-placement="top">
                <n-form-item label="Name" path="name">
                    <n-input v-model:value="form.name" placeholder="Enter name" />
                </n-form-item>

                <n-form-item label="Email" path="email">
                    <n-input v-model:value="form.email" placeholder="Enter email" />
                </n-form-item>

                <n-button type="primary" attr-type="submit" :loading="form.processing">
                    Save Changes
                </n-button>
            </n-form>
        </div>

    </AppLayout>
</template>
