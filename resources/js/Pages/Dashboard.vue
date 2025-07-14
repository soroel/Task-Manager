<script setup>
import { Head, router } from '@inertiajs/vue3';
import { reactive, ref, onMounted, onBeforeUnmount, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useToast } from 'vue-toastification';

const toast = useToast();

const props = defineProps({
  tasks: Array,
});

const taskForms = reactive({});
const originalStatuses = reactive({});

const validStatuses = ['Pending', 'In Progress', 'Completed'];

props.tasks?.forEach(task => {
  taskForms[task.id] = { status: task.status };
  originalStatuses[task.id] = task.status;
});

const hasUnsavedChanges = () => {
  return props.tasks?.some(task => taskForms[task.id].status !== originalStatuses[task.id]);
};

const handleBeforeUnload = (event) => {
  if (hasUnsavedChanges()) {
    event.preventDefault();
    event.returnValue = '';
  }
};

onMounted(() => {
  window.addEventListener('beforeunload', handleBeforeUnload);
});

onBeforeUnmount(() => {
  window.removeEventListener('beforeunload', handleBeforeUnload);
});

const updateStatus = (taskId) => {
  router.put(`/tasks/${taskId}/status`, {
    status: taskForms[taskId].status,
  }, {
    preserveScroll: true,
    onSuccess: () => {
      toast.success(`Task #${taskId} updated successfully!`);
      originalStatuses[taskId] = taskForms[taskId].status;
    },
    onError: (errors) => {
      for (const error in errors) {
        toast.error(errors[error]);
      }
    },
  });
};

const statusCounts = computed(() => {
  const counts = {
    Pending: 0,
    'In Progress': 0,
    Completed: 0,
  };

  props.tasks?.forEach(task => {
    if (validStatuses.includes(task.status)) {
      counts[task.status]++;
    }
  });

  return counts;
});
</script>

<template>
  <Head title="My Tasks" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800">My Tasks</h2>
      </div>
    </template>

    <div class="py-6">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow rounded">

          <!--Progress Indicator -->
          <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Task Progress</h3>
            <div class="flex items-center justify-between space-x-4">
              <div class="flex flex-col items-center text-xs">
                <div class="w-10 h-10 flex items-center justify-center rounded-full bg-red-100 text-red-700 font-bold">
                  {{ statusCounts.Pending }}
                </div>
                <span class="mt-1 text-red-600">Pending</span>
              </div>
              <div class="h-1 flex-1 bg-gray-200 rounded"></div>
              <div class="flex flex-col items-center text-xs">
                <div class="w-10 h-10 flex items-center justify-center rounded-full bg-yellow-100 text-yellow-800 font-bold">
                  {{ statusCounts['In Progress'] }}
                </div>
                <span class="mt-1 text-yellow-700">In Progress</span>
              </div>
              <div class="h-1 flex-1 bg-gray-200 rounded"></div>
              <div class="flex flex-col items-center text-xs">
                <div class="w-10 h-10 flex items-center justify-center rounded-full bg-green-100 text-green-700 font-bold">
                  {{ statusCounts.Completed }}
                </div>
                <span class="mt-1 text-green-700">Completed</span>
              </div>
            </div>
          </div>

          <!-- 📝 Task Table -->
          <div v-if="tasks.length === 0" class="text-gray-600">
            You currently have no tasks assigned.
          </div>

          <div v-else>
            <table class="w-full text-left border">
              <thead>
                <tr class="border-b bg-gray-100">
                  <th class="p-2">Title</th>
                  <th class="p-2">Description</th>
                  <th class="p-2">Deadline</th>
                  <th class="p-2">Status</th>
                  <th class="p-2">Update</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="task in tasks" :key="task.id" class="border-b">
                  <td class="p-2">{{ task.title }}</td>
                  <td class="p-2">{{ task.description }}</td>
                  <td class="p-2">{{ task.deadline }}</td>
                  <td class="p-2">
                    <span
                      class="inline-block px-2 py-1 rounded text-xs font-medium"
                      :class="{
                        'bg-red-100 text-red-800': taskForms[task.id].status === 'Pending',
                        'bg-yellow-100 text-yellow-800': taskForms[task.id].status === 'In Progress',
                        'bg-green-100 text-green-800': taskForms[task.id].status === 'Completed',
                      }"
                    >
                      {{ taskForms[task.id].status }}
                    </span>
                  </td>
                  <td class="p-2 flex items-center space-x-2">
                    <select
                      v-model="taskForms[task.id].status"
                      class="border rounded px-2 py-1"
                    >
                      <option value="Pending">Pending</option>
                      <option value="In Progress">In Progress</option>
                      <option value="Completed">Completed</option>
                    </select>
                    <button
                      @click="updateStatus(task.id)"
                      :disabled="taskForms[task.id].status === originalStatuses[task.id]"
                      class="px-3 py-1 text-white rounded transition"
                      :class="taskForms[task.id].status === originalStatuses[task.id]
                        ? 'bg-gray-400 cursor-not-allowed'
                        : 'bg-indigo-600 hover:bg-indigo-700'"
                    >
                      Save
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>


