<template>
  <div class="min-h-screen bg-gray-100 p-8">
    <div class="mb-4">
      <Link
        href="/dashboard"
        class="text-indigo-600 hover:text-indigo-800 text-sm font-medium"
      >
        ← Back to Dashboard
      </Link>
    </div>
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Admin Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Users Card -->
      <div
        class="bg-white shadow-md rounded-xl p-6 hover:shadow-lg transition duration-300 flex items-center justify-between"
      >
        <div>
          <h2 class="text-xl font-semibold text-gray-700 mb-1">User Management</h2>
          <p class="text-gray-500 text-sm">Create, update, and remove users</p>
        </div>
        <Link
          href="/admin/users"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
        >
          Manage Users
        </Link>
      </div>

      <!-- Tasks Card -->
      <div
        class="bg-white shadow-md rounded-xl p-6 hover:shadow-lg transition duration-300 flex items-center justify-between"
      >
        <div>
          <h2 class="text-xl font-semibold text-gray-700 mb-1">Task Management</h2>
          <p class="text-gray-500 text-sm">Assign, edit, and track tasks</p>
        </div>
        <Link
          href="/admin/tasks"
          class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
        >
          Manage Tasks
        </Link>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="mt-12">
      <h2 class="text-xl font-semibold text-gray-700 mb-4">Recent Activity</h2>
      <ul class="bg-white rounded-xl shadow divide-y divide-gray-200">
        <li class="p-4 text-sm text-gray-600">🟢 John updated Task #14 (In Progress)</li>
        <li class="p-4 text-sm text-gray-600">🟢 Admin added a new user: Alice</li>
        <li class="p-4 text-sm text-gray-600">🟡 Task #12 marked as Pending by Mark</li>
        <li class="p-4 text-sm text-gray-600">🔴 User Jane was deleted</li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>
