<!-- resources/js/Pages/Admin/Tasks/Edit.vue -->
<script setup>
import { useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useToast } from 'vue-toastification';

const toast = useToast();

const props = defineProps({
  task: Object,
  users: Array,
});

const form = useForm({
  title: props.task.title,
  description: props.task.description,
  user_id: props.task.user_id,
  deadline: props.task.deadline,
  status: props.task.status,
});

const updateTask = () => {
  form.put(`/admin/tasks/${props.task.id}`, {
    preserveScroll: true,
    onSuccess: () => {
      toast.success('Task updated successfully!');
      router.visit('/admin/tasks');
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
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800">Edit Task</h2>
    </template>

    <div class="max-w-2xl mx-auto p-6 bg-white shadow rounded mt-8">
      <form @submit.prevent="updateTask">
        <div class="mb-4">
          <label class="block mb-1 font-medium">Title</label>
          <input
            v-model="form.title"
            type="text"
            class="w-full border rounded px-3 py-2"
          />
        </div>

        <div class="mb-4">
          <label class="block mb-1 font-medium">Description</label>
          <textarea
            v-model="form.description"
            class="w-full border rounded px-3 py-2"
          ></textarea>
        </div>

        <div class="mb-4">
          <label class="block mb-1 font-medium">Assign To</label>
          <select v-model="form.user_id" class="w-full border rounded px-3 py-2">
            <option v-for="user in users" :key="user.id" :value="user.id">
              {{ user.name }}
            </option>
          </select>
        </div>

        <div class="mb-4">
          <label class="block mb-1 font-medium">Deadline</label>
          <input
            v-model="form.deadline"
            type="date"
            class="w-full border rounded px-3 py-2"
          />
        </div>

        <div class="mb-4">
          <label class="block mb-1 font-medium">Status</label>
          <select v-model="form.status" class="w-full border rounded px-3 py-2">
            <option value="Pending">Pending</option>
            <option value="In Progress">In Progress</option>
            <option value="Completed">Completed</option>
          </select>
        </div>

        <div class="flex justify-between mt-6">
          <a
            href="/admin/tasks"
            class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300"
          >
            Cancel
          </a>
          <button
            type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            :disabled="form.processing"
          >
            Save Changes
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
