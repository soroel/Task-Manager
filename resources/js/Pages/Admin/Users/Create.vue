<script setup>
import { onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';

const toast = useToast();

const form = useForm({
    name: '',
    email: '',
    password: '',
    role: 'user',
});

onMounted(() => {
    form.reset(); 
});

const submit = () => {
    form.post(route('admin.users.store'), {
        onSuccess: () => {
            toast.success('User created successfully!');
            form.reset();
        },
        onError: () => {
            toast.error('Failed to create user. Please check the form for errors.');
        },
    });
};
</script>



<template>
    <Head title="Add User" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">Add New User</h2>
        </template>

        <div class="p-6">
            <form @submit.prevent="submit" class="space-y-4 max-w-md">
                <div>
                    <label>Name</label>
                    <input v-model="form.name" type="text" class="border rounded w-full p-2" />
                    <span v-if="form.errors.name" class="text-red-600">{{ form.errors.name }}</span>
                </div>

                <div>
                    <label>Email</label>
                    <input v-model="form.email" type="email" class="border rounded w-full p-2" />
                    <span v-if="form.errors.email" class="text-red-600">{{ form.errors.email }}</span>
                </div>

                <div>
                    <label>Password</label>
                    <input v-model="form.password" type="password" class="border rounded w-full p-2" />
                    <span v-if="form.errors.password" class="text-red-600">{{ form.errors.password }}</span>
                </div>

                <div>
                    <label>Role</label>
                    <select v-model="form.role" class="border rounded w-full p-2">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                    <span v-if="form.errors.role" class="text-red-600">{{ form.errors.role }}</span>
                </div>

                <button
                    type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700"
                    :disabled="form.processing"
                >
                    Create User
                </button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
