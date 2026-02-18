import { type Ref, ref, computed, shallowRef } from 'vue'
import type { PaginationMeta } from '@shared/types'

export function usePagination<T>(
  fetchFn: (page: number, limit: number) => Promise<{ data: T[]; meta: PaginationMeta }>,
  defaultLimit = 20,
) {
  const items: Ref<T[]> = shallowRef([])
  const initialLoading = ref(true)
  const loading = ref(false)
  const error = ref<string | null>(null)
  const meta = ref<PaginationMeta>({
    total: 0,
    page: 1,
    limit: defaultLimit,
    totalPages: 0,
  })

  const hasPrevious = computed(() => meta.value.page > 1)
  const hasNext = computed(() => meta.value.page < meta.value.totalPages)

  async function fetchPage(page: number) {
    loading.value = true
    error.value = null

    try {
      const response = await fetchFn(page, meta.value.limit)
      items.value = response.data
      meta.value = response.meta
    } catch (e) {
      error.value = e instanceof Error ? e.message : 'An error occurred'
      console.error(e)
    } finally {
      loading.value = false
      initialLoading.value = false
    }
  }

  function goToPage(page: number) {
    if (page >= 1 && page <= meta.value.totalPages) {
      fetchPage(page)
    }
  }

  function nextPage() {
    if (hasNext.value) {
      goToPage(meta.value.page + 1)
    }
  }

  function previousPage() {
    if (hasPrevious.value) {
      goToPage(meta.value.page - 1)
    }
  }

  return {
    items,
    initialLoading,
    loading,
    error,
    meta,
    hasPrevious,
    hasNext,
    fetchPage,
    goToPage,
    nextPage,
    previousPage,
  }
}
