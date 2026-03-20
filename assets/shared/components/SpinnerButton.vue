<script setup lang="ts">
import { Loader2 } from 'lucide-vue-next'

defineProps<{
  loading: boolean
  label: string
  loadingLabel?: string
  disabled?: boolean
}>()
</script>

<template>
  <button class="btn btn-primary" :disabled="loading || disabled" v-bind="$attrs">
    <Loader2 v-if="loading" class="inline w-4 h-4 mr-1 animate-spin" />
    {{ loading ? (loadingLabel ?? label) : label }}
  </button>
</template>

<script lang="ts">
export default {
  inheritAttrs: false,
}
</script>
