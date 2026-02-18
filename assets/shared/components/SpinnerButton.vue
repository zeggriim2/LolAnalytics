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
