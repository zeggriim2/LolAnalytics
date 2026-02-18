<script setup lang="ts">
import { computed } from 'vue'
import type { PaginationMeta } from '@shared/types'

const props = defineProps<{
  meta: PaginationMeta
  hasPrevious: boolean
  hasNext: boolean
}>()

const emit = defineEmits<{
  previous: []
  next: []
  goToPage: [page: number]
}>()

const pages = computed(() => {
  const total = props.meta.totalPages
  const current = props.meta.page
  const result: (number | '...')[] = []

  if (total <= 7) {
    for (let i = 1; i <= total; i++) result.push(i)
    return result
  }

  result.push(1)

  if (current > 3) {
    result.push('...')
  }

  const start = Math.max(2, current - 1)
  const end = Math.min(total - 1, current + 1)

  for (let i = start; i <= end; i++) {
    result.push(i)
  }

  if (current < total - 2) {
    result.push('...')
  }

  result.push(total)

  return result
})
</script>

<template>
  <div
    v-if="meta.totalPages > 1"
    class="flex justify-between items-center py-4 mt-4 border-t border-lol-border"
  >
    <div class="text-lol-muted text-sm">
      Page {{ meta.page }} / {{ meta.totalPages }} ({{ meta.total }} results)
    </div>
    <div class="flex gap-1 items-center">
      <button
        class="btn btn-secondary px-3 py-1.5 text-xs min-w-[2.2rem] text-center disabled:opacity-40 disabled:cursor-not-allowed"
        :disabled="!hasPrevious"
        @click="emit('previous')"
      >
        Previous
      </button>
      <template v-for="(page, index) in pages" :key="index">
        <span v-if="page === '...'" class="px-1 py-1.5 text-lol-muted">...</span>
        <button
          v-else
          class="btn px-3 py-1.5 text-xs min-w-[2.2rem] text-center"
          :class="page === meta.page ? 'btn-primary' : 'btn-secondary'"
          @click="emit('goToPage', page)"
        >
          {{ page }}
        </button>
      </template>
      <button
        class="btn btn-secondary px-3 py-1.5 text-xs min-w-[2.2rem] text-center disabled:opacity-40 disabled:cursor-not-allowed"
        :disabled="!hasNext"
        @click="emit('next')"
      >
        Next
      </button>
    </div>
  </div>
</template>
