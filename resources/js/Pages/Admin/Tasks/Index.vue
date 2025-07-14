<script setup>
import { router } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import { Pencil, Trash2 } from 'lucide-vue-next';
import { useToast } from 'vue-toastification';

const props = defineProps({
  tasks: Object,
});

const toast = useToast();

function deleteTask(id) {
  if (confirm("Are you sure you want to delete this task?")) {
    router.delete(`/admin/tasks/${id}`, {
      preserveScroll: true,
      onSuccess: () => {
        toast.success('Task deleted successfully!');
      },
      onError: () => {
        toast.error('Failed to delete task. Try again.');
      },
    });
  }
}

function getInitials(name) {
  return name
    ? name.split(' ').map(n => n.charAt(0).toUpperCase()).join('')
    : '';
}

function goToPage(url) {
  if (url) router.visit(url, { preserveScroll: true });
}
</script>

<template>
  <!--Back Button -->
  <div class="mt-4">
    <a
      href="/admin/dashboard"
      class="inline-flex items-center text-sm text-gray-700 hover:text-blue-600 transition"
    >
      ← Back to Dashboard
    </a>
  </div>

  <div class="mt-10 px-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Assigned Tasks</h1>
      <button
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
        @click="router.visit('/admin/tasks/create')"
      >
        + Assign New Task
      </button>
    </div>

    <div class="overflow-x-auto bg-white shadow ring-1 ring-gray-200 rounded-lg">
      <table class="min-w-full table-auto text-sm">
        <thead class="bg-gray-100 text-left text-gray-600 uppercase tracking-wide">
          <tr>
            <th class="px-4 py-3">ID</th>
            <th class="px-4 py-3">Title</th>
            <th class="px-4 py-3">Assigned To</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Deadline</th>
            <th class="px-4 py-3">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr
            v-for="task in props.tasks.data"
            :key="task.id"
            class="hover:bg-gray-50 transition"
          >
            <td class="px-4 py-2">{{ task.id }}</td>
            <td class="px-4 py-2 font-medium">{{ task.title }}</td>
            <td class="px-4 py-2 flex items-center gap-2">
              <div
                class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold"
                v-if="task.assigned_user"
              >
                {{ getInitials(task.assigned_user.name) }}
              </div>
              <span>{{ task.assigned_user?.name }}</span>
            </td>
            <td class="px-4 py-2">
              <span
                :class="[
                  'px-2 py-1 rounded text-xs font-semibold',
                  task.status === 'Pending'
                    ? 'bg-yellow-200 text-yellow-800'
                    : task.status === 'In Progress'
                    ? 'bg-blue-200 text-blue-800'
                    : 'bg-green-200 text-green-800'
                ]"
              >
                {{ task.status }}
              </span>
            </td>
            <td class="px-4 py-2">{{ task.deadline }}</td>
            <td class="px-4 py-2 space-x-2">
              <button
                @click="router.visit(`/admin/tasks/${task.id}/edit`)"
                class="text-indigo-600 hover:text-indigo-800"
                title="Edit"
              >
                <Pencil class="w-5 h-5 inline" />
              </button>
              <button
                @click="deleteTask(task.id)"
                class="text-red-600 hover:text-red-800"
                title="Delete"
              >
                <Trash2 class="w-5 h-5 inline" />
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="mt-6 flex justify-center gap-2 flex-wrap">
        <button
          v-for="link in tasks.links"
          :key="link.label"
          v-html="link.label"
          @click="goToPage(link.url)"
          :disabled="!link.url"
          class="px-3 py-1 border rounded text-sm transition"
          :class="{
            'bg-blue-600 text-white': link.active,
            'text-gray-500 cursor-not-allowed': !link.url,
            'hover:bg-gray-100': link.url && !link.active
          }"
        ></button>
      </div>
    </div>
  </div>
</template>
