<script setup lang="ts">
defineProps<{
  label: string
  count: number
  active: boolean
}>()

const emit = defineEmits<{ select: [] }>()
</script>

<template>
  <div class="tab-item" :class="{ 'tab-item--active': active }" @click="emit('select')">
    {{ label }}
    <span class="text-xs opacity-60 ml-1">({{ count }})</span>
  </div>
</template>
