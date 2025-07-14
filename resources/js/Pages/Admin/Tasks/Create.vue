<script setup>
import { useForm, router } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

import { useToast } from 'vue-toastification';

const toast = useToast();

// import TextArea from '@/Components/TextArea.vue';
// import Select from '@/Components/Select.vue';



const props = defineProps({
    users: Array
});

const form = useForm({
    title: '',
    description: '',
    user_id: '',
    deadline: '',
});

const submit = () => {
    form.post(route('admin.tasks.store'), {
        onSuccess: () => {
            toast.success('Task assigned successfully!');
            form.reset();
        },
        onError: (errors) => {
            for (const error in errors) {
                toast.error(errors[error]);
            }
        },
    });
};
</script>

<template>
    <Head title="Assign Task" />

    <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Assign New Task</h2>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <InputLabel for="title" value="Task Title" />
                <TextInput id="title" v-model="form.title" class="w-full" />
                <InputError :message="form.errors.title" />
            </div>

            <div>
                <InputLabel for="description" value="Description" />
                <textarea v-model="form.description" class="w-full border rounded p-2" rows="4" />
                <InputError :message="form.errors.description" />
            </div>

            <div>
                <InputLabel for="user_id" value="Assign to User" />
                <select v-model="form.user_id" class="w-full border rounded p-2">
                    <option value="">Select User</option>
                    <option v-for="user in users" :key="user.id" :value="user.id">
                        {{ user.name }}
                    </option>
                </select>
                <InputError :message="form.errors.user_id" />
            </div>

            <div>
                <InputLabel for="deadline" value="Deadline" />
                <input type="date" v-model="form.deadline" class="w-full border rounded p-2" />
                <InputError :message="form.errors.deadline" />
            </div>

            <PrimaryButton :disabled="form.processing">Assign Task</PrimaryButton>
        </form>
    </div>
</template>
