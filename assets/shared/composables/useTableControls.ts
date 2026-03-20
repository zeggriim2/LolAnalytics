import { ref, computed, reactive } from 'vue'
import { useDebounce } from '@vueuse/core'

export type SortDir = 'asc' | 'desc' | null

export function useTableControls<T>(source: () => T[], debounceMs = 300) {
  const search = ref('')
  const debouncedSearch = useDebounce(search, debounceMs)
  const sortKey = ref<string | null>(null)
  const sortDir = ref<SortDir>(null)

  function toggleSort(key: string) {
    if (sortKey.value === key) {
      if (sortDir.value === 'asc') {
        sortDir.value = 'desc'
      } else {
        sortKey.value = null
        sortDir.value = null
      }
    } else {
      sortKey.value = key
      sortDir.value = 'asc'
    }
  }

  const rows = computed(() => {
    const q = debouncedSearch.value.toLowerCase().trim()
    let result: T[] = source()

    if (q) {
      result = result.filter((item) =>
        Object.values(item as Record<string, unknown>).some((v) =>
          String(v ?? '')
            .toLowerCase()
            .includes(q),
        ),
      )
    }

    if (sortKey.value && sortDir.value) {
      const key = sortKey.value
      const dir = sortDir.value
      result = [...result].sort((a, b) => {
        const av = (a as Record<string, unknown>)[key] ?? ''
        const bv = (b as Record<string, unknown>)[key] ?? ''
        const cmp = String(av).localeCompare(String(bv), undefined, { numeric: true })
        return dir === 'asc' ? cmp : -cmp
      })
    }

    return result
  })

  return reactive({ search, sortKey, sortDir, toggleSort, rows })
}
