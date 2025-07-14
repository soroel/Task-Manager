<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';

const toast = useToast();

const props = defineProps(['user']);

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    role: props.user.role,
});

const submit = () => {
    form.put(route('admin.users.update', props.user.id), {
        onSuccess: () => {
            toast.success('User updated successfully!');
        },
        onError: () => {
            toast.error('Failed to update user. Please check the form for errors.');
        },
    });
};
</script>

<template>
    <Head title="Edit User" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">Edit User</h2>
        </template>

        <div class="px-6 pt-6">
            <!-- 🔙 Back Link -->
            <div class="mb-6">
                <a
                    href="/admin/users"
                    class="inline-flex items-center text-sm text-gray-600 hover:text-blue-600 transition"
                >
                    ← Back to Users
                </a>
            </div>

            <form @submit.prevent="submit" class="space-y-5 max-w-lg bg-white p-6 rounded shadow">
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Name</label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
                    />
                    <span v-if="form.errors.name" class="text-red-600 text-sm">{{ form.errors.name }}</span>
                </div>

                <div>
                    <label class="block mb-1 font-medium text-gray-700">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
                    />
                    <span v-if="form.errors.email" class="text-red-600 text-sm">{{ form.errors.email }}</span>
                </div>

                <div>
                    <label class="block mb-1 font-medium text-gray-700">Role</label>
                    <select
                        v-model="form.role"
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
                    >
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                    <span v-if="form.errors.role" class="text-red-600 text-sm">{{ form.errors.role }}</span>
                </div>

                <button
                    type="submit"
                    class="bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700 transition disabled:opacity-50"
                    :disabled="form.processing"
                >
                    Save Changes
                </button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
