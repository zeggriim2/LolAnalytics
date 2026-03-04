import { ref } from 'vue'
import type { Champion } from '@shared/types'
import { championApi } from '@shared/api/championApi'

const champions = ref<Map<number, Champion>>(new Map())
const loaded = ref(false)
const loading = ref(false)

export function useChampions() {
  async function load() {
    if (loaded.value || loading.value) return
    loading.value = true
    try {
      const response = await championApi.getChampions()
      const map = new Map<number, Champion>()
      for (const champion of response.data) {
        map.set(parseInt(champion.championKey), champion)
      }
      champions.value = map
      loaded.value = true
    } finally {
      loading.value = false
    }
  }

  function findById(championId: number): Champion | undefined {
    return champions.value.get(championId)
  }

  function getImageUrl(champion: Champion): string {
    return `https://ddragon.leagueoflegends.com/cdn/${champion.version}/img/champion/${champion.imageFull}`
  }

  return { load, findById, getImageUrl, loaded }
}
