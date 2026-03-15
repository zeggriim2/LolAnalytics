import { ref } from 'vue'
import { FetchError } from 'ofetch'
import { api } from '@shared/api/client'
import type { Summoner } from '@shared/types'

export function useSummoner(puuid: string) {
  const summoner = ref<Summoner | null>(null)
  const loading = ref(true)
  const error = ref<string | null>(null)
  const notFound = ref(false)

  async function load() {
    loading.value = true
    notFound.value = false
    error.value = null
    try {
      const response = await api.getSummoner(puuid)
      summoner.value = response.data
    } catch (e) {
      if (e instanceof FetchError && e.response?.status === 404) {
        notFound.value = true
      } else {
        error.value = 'Failed to load summoner details'
        console.error(e)
      }
    } finally {
      loading.value = false
    }
  }

  return { summoner, loading, error, notFound, load }
}
