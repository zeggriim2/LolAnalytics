<script setup lang="ts">
import type { Component } from 'vue'

defineProps<{
  value: string | number
  label: string
  icon?: Component
  iconColor?: string
  sub?: string
}>()
</script>

<template>
  <div class="admin-card p-6 flex items-start justify-between gap-4">
    <div class="min-w-0">
      <p class="text-admin-text text-sm mb-1">{{ label }}</p>
      <p class="text-admin-heading text-3xl font-bold leading-none">{{ value }}</p>
      <p v-if="sub" class="text-admin-text text-xs mt-2">{{ sub }}</p>
    </div>
    <div
      v-if="icon"
      class="shrink-0 w-12 h-12 rounded-xl flex items-center justify-center"
      :class="iconColor ?? 'bg-admin-primary/10'"
    >
      <component
        :is="icon"
        class="w-6 h-6"
        :class="iconColor ? 'text-white' : 'text-admin-primary'"
      />
    </div>
  </div>
</template>
