<script setup lang="ts">
import { RouterLink } from 'vue-router'
import { ChevronRight } from 'lucide-vue-next'

defineProps<{
  crumbs: Array<{ label: string; to?: string }>
}>()
</script>

<template>
  <nav class="flex items-center gap-1 text-sm" aria-label="Breadcrumb">
    <template v-for="(crumb, i) in crumbs" :key="i">
      <ChevronRight v-if="i > 0" class="w-3.5 h-3.5 text-admin-border shrink-0" />
      <RouterLink
        v-if="crumb.to && i < crumbs.length - 1"
        :to="crumb.to"
        class="text-admin-text hover:text-admin-heading transition-colors no-underline"
      >
        {{ crumb.label }}
      </RouterLink>
      <span
        v-else
        class="font-medium"
        :class="i === crumbs.length - 1 ? 'text-admin-heading' : 'text-admin-text'"
      >
        {{ crumb.label }}
      </span>
    </template>
  </nav>
</template>
