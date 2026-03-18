import { ref } from 'vue'
import { defineStore } from 'pinia'
import type { Champion } from '@shared/types'
import { championApi } from '@shared/api/championApi'

export const useChampionsStore = defineStore('champions', () => {
  const championsByVersion = ref<Map<string, Map<number, Champion>>>(new Map())
  const loadingVersions = ref<Set<string>>(new Set())

  async function load(version: string) {
    if (championsByVersion.value.has(version) || loadingVersions.value.has(version)) return
    loadingVersions.value.add(version)
    try {
      const response = await championApi.getChampions(version)
      const map = new Map<number, Champion>()
      for (const champion of response.data) {
        map.set(parseInt(champion.championKey), champion)
      }
      championsByVersion.value.set(version, map)
    } finally {
      loadingVersions.value.delete(version)
    }
  }

  function findById(championId: number, version: string): Champion | undefined {
    return championsByVersion.value.get(version)?.get(championId)
  }

  function getImageUrl(champion: Champion): string {
    return `/images/champions/${champion.version}/${champion.imageFull}`
  }

  return { load, findById, getImageUrl }
})
