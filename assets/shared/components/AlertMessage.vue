<script setup lang="ts">
defineProps<{
  type: 'success' | 'error'
  message: string
}>()
</script>

<template>
  <div
    class="px-4 py-3 mb-4 rounded"
    :class="
      type === 'success'
        ? 'bg-lol-win/10 border border-lol-win text-lol-win'
        : 'bg-lol-loss/10 border border-lol-loss text-lol-loss'
    "
  >
    {{ message }}
  </div>
</template>
