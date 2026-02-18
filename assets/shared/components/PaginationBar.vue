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
  <div v-if="meta.totalPages > 1" class="pagination">
    <div class="pagination-info">
      Page {{ meta.page }} / {{ meta.totalPages }} ({{ meta.total }} results)
    </div>
    <div class="pagination-controls">
      <button
        class="btn btn-secondary pagination-btn"
        :disabled="!hasPrevious"
        @click="emit('previous')"
      >
        Previous
      </button>
      <template v-for="(page, index) in pages" :key="index">
        <span v-if="page === '...'" class="pagination-ellipsis">...</span>
        <button
          v-else
          class="btn pagination-btn"
          :class="page === meta.page ? 'btn-primary' : 'btn-secondary'"
          @click="emit('goToPage', page)"
        >
          {{ page }}
        </button>
      </template>
      <button class="btn btn-secondary pagination-btn" :disabled="!hasNext" @click="emit('next')">
        Next
      </button>
    </div>
  </div>
</template>

<style scoped>
.pagination {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 0;
  margin-top: 1rem;
  border-top: 1px solid var(--border-color);
}

.pagination-info {
  color: var(--text-muted);
  font-size: 0.875rem;
}

.pagination-controls {
  display: flex;
  gap: 0.25rem;
  align-items: center;
}

.pagination-btn {
  padding: 0.4rem 0.75rem;
  font-size: 0.8rem;
  min-width: 2.2rem;
  text-align: center;
}

.pagination-btn:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

.pagination-ellipsis {
  padding: 0.4rem 0.25rem;
  color: var(--text-muted);
}
</style>
