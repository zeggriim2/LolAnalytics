<script setup lang="ts">
defineProps<{
  type: 'success' | 'error'
  message: string
}>()
</script>

<template>
  <div :class="['alert', `alert-${type}`]">
    {{ message }}
  </div>
</template>

<style scoped>
.alert {
  padding: 0.75rem 1rem;
  margin-bottom: 1rem;
  border-radius: 4px;
}

.alert-success {
  background-color: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.alert-error {
  background-color: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}
</style>
