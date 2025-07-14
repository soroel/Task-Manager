<script setup>
import { router } from '@inertiajs/vue3';
import { Pencil, Trash2 } from 'lucide-vue-next';
import { useToast } from 'vue-toastification';

const toast = useToast();

const props = defineProps({
  users: Object,
});

function deleteUser(id) {
  if (confirm("Are you sure you want to delete this user?")) {
    router.delete(`/admin/users/${id}`, {
      preserveScroll: true,
      onSuccess: () => {
        toast.success('User deleted successfully!');
        router.reload();
      },
      onError: (errors) => {
        for (const error in errors) {
          toast.error(errors[error]);
        }
      },
    });
  }
}

function getInitials(name) {
  return name
    .split(' ')
    .map(part => part.charAt(0).toUpperCase())
    .join('');
}

function goToPage(url) {
  if (url) router.visit(url, { preserveScroll: true });
}
</script>

<template>
  <div class="mt-10 px-6">
    <!-- 🔙 Back Button -->
    <div class="mb-4">
      <a
        href="/admin/dashboard"
        class="inline-flex items-center text-sm text-gray-700 hover:text-blue-600 transition"
      >
        ← Back to Dashboard
      </a>
    </div>

    <div class="mb-6 flex justify-between items-center">
      <h1 class="text-3xl font-bold">User Management</h1>
      <a
        href="/admin/users/create"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
      >
        + Add User
      </a>
    </div>

    <p class="text-gray-600 mb-4">
      Total Users: <span class="font-medium">{{ users.total }}</span>
    </p>

    <div class="overflow-x-auto bg-white shadow ring-1 ring-gray-200 rounded-lg">
      <table class="min-w-full table-auto text-sm">
        <thead class="bg-gray-100 text-left text-gray-600 uppercase tracking-wide">
          <tr>
            <th class="px-4 py-3">#</th>
            <th class="px-4 py-3">Profile</th>
            <th class="px-4 py-3">Email</th>
            <th class="px-4 py-3">Role</th>
            <th class="px-4 py-3">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-50 transition">
            <td class="px-4 py-2">{{ user.id }}</td>
            <td class="px-4 py-2 flex items-center gap-2">
              <div
                class="w-9 h-9 flex items-center justify-center rounded-full bg-indigo-100 text-indigo-700 font-semibold text-sm"
                :title="user.name"
              >
                {{ getInitials(user.name) }}
              </div>
              <span>{{ user.name }}</span>
            </td>
            <td class="px-4 py-2">{{ user.email }}</td>
            <td class="px-4 py-2 capitalize">{{ user.role }}</td>
            <td class="px-4 py-2 space-x-2">
              <a
                :href="`/admin/users/${user.id}/edit`"
                class="text-indigo-600 hover:text-indigo-800"
                title="Edit"
              >
                <Pencil class="w-5 h-5 inline" />
              </a>
              <button
                @click="deleteUser(user.id)"
                class="text-red-600 hover:text-red-800"
                title="Delete"
              >
                <Trash2 class="w-5 h-5 inline" />
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center gap-2 flex-wrap">
      <button
        v-for="link in users.links"
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
</template>
