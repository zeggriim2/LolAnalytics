<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter, RouterLink } from 'vue-router'
import { api } from '@shared/api/client'
import type { Summoner } from '@shared/types'

const route = useRoute()
const router = useRouter()
const allSummoners = ref<Summoner[]>([])
const loading = ref(true)
const error = ref<string | null>(null)
const searchQuery = ref((route.query.q as string) || '')

const filteredSummoners = computed(() => {
  if (!searchQuery.value.trim()) {
    return allSummoners.value
  }
  const query = searchQuery.value.toLowerCase()
  return allSummoners.value.filter((s) => s.riotId.toLowerCase().includes(query))
})

watch(
  () => route.query.q,
  (newQ) => {
    searchQuery.value = (newQ as string) || ''
  },
)

onMounted(async () => {
  try {
    const response = await api.getSummoners()
    allSummoners.value = response.data
  } catch (e) {
    error.value = 'Failed to load summoners'
    console.error(e)
  } finally {
    loading.value = false
  }
})

function onSearch() {
  router.replace({ name: 'summoner-search', query: { q: searchQuery.value.trim() || undefined } })
}
</script>

<template>
  <div class="container">
    <header class="page-header py-8">
      <h2>Search Summoners</h2>
    </header>

    <form class="search-box mb-8 max-w-full" @submit.prevent="onSearch">
      <input
        v-model="searchQuery"
        type="text"
        class="search-input"
        placeholder="Search by Riot ID (e.g., Faker#KR1)"
        @keyup.enter="onSearch"
      />
    </form>

    <div v-if="loading" class="loading">Loading...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <template v-else>
      <p v-if="searchQuery && filteredSummoners.length === 0" class="loading">
        No summoners found for "{{ searchQuery }}"
      </p>
      <div v-else class="grid grid-cols-[repeat(auto-fill,minmax(280px,1fr))] gap-4">
        <RouterLink
          v-for="summoner in filteredSummoners"
          :key="summoner.puuid"
          :to="`/summoners/${summoner.puuid}`"
          class="bg-lol-card rounded-lg p-6 border border-lol-border no-underline text-lol-text transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:border-lol-gold"
        >
          <h3 class="text-lol-gold mb-2">{{ summoner.riotId }}</h3>
          <p class="text-lol-muted text-sm">
            Level {{ summoner.summonerLevel }} · {{ summoner.platform.toUpperCase() }}
          </p>
        </RouterLink>
      </div>
    </template>
  </div>
</template>
