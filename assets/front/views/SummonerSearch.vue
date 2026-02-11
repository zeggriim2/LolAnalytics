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
    const response = await api.getSummoners(200)
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
    <header class="page-header">
      <h2>Search Summoners</h2>
    </header>

    <form
      class="search-box"
      style="margin-bottom: 2rem; max-width: 100%"
      @submit.prevent="onSearch"
    >
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
      <div v-else class="summoner-grid">
        <RouterLink
          v-for="summoner in filteredSummoners"
          :key="summoner.puuid"
          :to="`/summoners/${summoner.puuid}`"
          class="summoner-card"
        >
          <h3>{{ summoner.riotId }}</h3>
          <p class="meta">
            Level {{ summoner.summonerLevel }} · {{ summoner.platform.toUpperCase() }}
          </p>
        </RouterLink>
      </div>
    </template>
  </div>
</template>

<style scoped>
.summoner-card {
  background-color: var(--secondary-color);
  border-radius: 8px;
  padding: 1.5rem;
  border: 1px solid var(--border-color);
  text-decoration: none;
  color: var(--text-color);
  transition:
    transform 0.2s,
    box-shadow 0.2s;

  h3 {
    color: var(--accent-color);
    margin-bottom: 0.5rem;
  }

  meta {
    color: var(--text-muted);
    font-size: 0.875rem;
  }

  &:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    border-color: var(--accent-color);
  }
}

.summoner-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1rem;
}
</style>
