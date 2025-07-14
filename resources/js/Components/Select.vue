<template>
  <select
    v-bind="$attrs"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
  >
    <slot />
  </select>
</template>

<script setup>
defineProps(['modelValue']);
defineEmits(['update:modelValue']);
</script>
