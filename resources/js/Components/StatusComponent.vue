<template lang="">
  <span
    class="flex w-fit items-center animate-pulse gap-1.5 rounded-full px-2 py-0.5 text-sm ring-1"
    :class="statusClasses"
  >
    {{ status }}
  </span>
</template>
<script setup>
import { computed } from 'vue';

  const { status } = defineProps({
    status: {
      type: String,
      default: "pending",
    }
  });

  const statusClasses = computed(() => {
    switch (status) {
      case 'success':
        return 'bg-green-500 text-green-600 ring-green-500 dark:bg-transparent dark:text-emerald-500 dark:ring-emerald-500'
      case 'processing':
        return 'bg-yellow-500 text-yellow-600 ring-yellow-500 dark:bg-transparent dark:text-yellow-500 dark:ring-yellow-500'
      case 'error':
        return 'bg-red-500 text-red-600 ring-red-500 dark:bg-transparent dark:text-red-500 dark:ring-red-500'
      default:
        return 'bg-gray-500 text-gray-600 ring-gray-500 dark:bg-transparent dark:text-gray-500 dark:ring-gray-500'
    }
  });
</script>
<style lang="">
  
</style>