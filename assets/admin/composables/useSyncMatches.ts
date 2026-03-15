import { ref } from 'vue'
import { matchApi } from '@shared/api/matchApi'

export function useSyncMatches(puuid: string, onSuccess: () => void) {
  const loading = ref(false)
  const error = ref<string | null>(null)

  async function sync() {
    loading.value = true
    error.value = null
    try {
      await matchApi.syncMatchesBySummoner(puuid)
      onSuccess()
    } catch (e) {
      error.value = 'Failed to sync matches'
      console.error(e)
    } finally {
      loading.value = false
    }
  }

  return { loading, error, sync }
}
