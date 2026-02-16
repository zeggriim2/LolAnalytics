<script setup lang="ts">
defineProps<{
  loading: boolean
  label: string
  loadingLabel?: string
  disabled?: boolean
}>()
</script>

<template>
  <button class="btn btn-primary" :disabled="loading || disabled" v-bind="$attrs">
    <span v-if="loading" class="spinner"></span>
    {{ loading ? (loadingLabel ?? label) : label }}
  </button>
</template>

<script lang="ts">
export default {
  inheritAttrs: false,
}
</script>

<style scoped>
.spinner {
  display: inline-block;
  width: 0.75rem;
  height: 0.75rem;
  border: 2px solid currentColor;
  border-right-color: transparent;
  border-radius: 50%;
  animation: spin 0.6s linear infinite;
  margin-right: 0.25rem;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>
