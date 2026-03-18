import { useChampionsStore } from '@shared/stores/useChampionsStore'

export function useChampions() {
  const store = useChampionsStore()
  return {
    load: store.load,
    findById: store.findById,
    getImageUrl: store.getImageUrl,
  }
}
