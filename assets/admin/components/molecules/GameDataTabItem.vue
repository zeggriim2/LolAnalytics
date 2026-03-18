<script setup lang="ts">
defineProps<{
  label: string
  count: number
  active: boolean
  syncing: boolean
  disabled: boolean
}>()

const emit = defineEmits<{
  select: []
  sync: []
}>()
</script>

<template>
  <div class="inline-flex items-center gap-1">
    <button
      class="btn ml-2"
      :class="active ? 'btn-primary' : 'btn-secondary'"
      @click="emit('select')"
    >
      {{ label }} ({{ count }})
    </button>
    <button class="btn" :disabled="disabled" @click="emit('sync')">
      <span v-if="syncing" class="spinner"></span>
      {{ syncing ? '...' : 'Sync' }}
    </button>
  </div>
</template>
