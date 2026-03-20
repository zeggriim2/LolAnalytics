<script setup lang="ts">
import type { SortDir } from '@shared/composables/useTableControls'
import { ChevronsUpDown, ChevronUp, ChevronDown } from 'lucide-vue-next'

defineProps<{
  label: string
  sortKey: string
  activeSortKey: string | null
  sortDir: SortDir
}>()

const emit = defineEmits<{ sort: [key: string] }>()
</script>

<template>
  <th class="sortable-th" @click="emit('sort', sortKey)">
    <span class="flex items-center gap-1">
      {{ label }}
      <ChevronUp
        v-if="activeSortKey === sortKey && sortDir === 'asc'"
        class="w-3 h-3 text-lol-gold shrink-0"
      />
      <ChevronDown
        v-else-if="activeSortKey === sortKey && sortDir === 'desc'"
        class="w-3 h-3 text-lol-gold shrink-0"
      />
      <ChevronsUpDown v-else class="w-3 h-3 opacity-30 shrink-0" />
    </span>
  </th>
</template>
