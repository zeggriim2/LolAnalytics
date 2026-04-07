<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter, RouterLink } from 'vue-router'
import { api } from '@shared/api/client'
import type { Summoner } from '@shared/types'
import { Search, User } from 'lucide-vue-next'

const route = useRoute()
const router = useRouter()
const allSummoners = ref<Summoner[]>([])
const loading = ref(true)
const error = ref<string | null>(null)
const searchQuery = ref((route.query.q as string) || '')

const filteredSummoners = computed(() => {
  if (!searchQuery.value.trim()) return allSummoners.value
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
    error.value = 'Impossible de charger les invocateurs'
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
  <div class="container py-10">
    <!-- Page header -->
    <div class="mb-8">
      <h2 class="text-2xl font-bold text-lol-text mb-1">Invocateurs</h2>
      <p class="text-lol-muted text-sm">
        {{ allSummoners.length }} invocateur{{ allSummoners.length > 1 ? 's' : '' }} enregistré{{
          allSummoners.length > 1 ? 's' : ''
        }}
      </p>
    </div>

    <!-- Search -->
    <form class="mb-8 max-w-xl" @submit.prevent="onSearch">
      <div class="relative">
        <Search
          class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-lol-muted pointer-events-none"
        />
        <input
          v-model="searchQuery"
          type="text"
          class="search-input pl-11 py-3"
          placeholder="Rechercher par Riot ID (ex : Faker#KR1)"
        />
      </div>
    </form>

    <div v-if="loading" class="loading">Chargement…</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <template v-else>
      <p
        v-if="searchQuery && filteredSummoners.length === 0"
        class="text-lol-muted text-center py-12"
      >
        Aucun invocateur trouvé pour
        <span class="text-lol-text">"{{ searchQuery }}"</span>
      </p>

      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        <RouterLink
          v-for="summoner in filteredSummoners"
          :key="summoner.puuid"
          :to="`/summoners/${summoner.puuid}`"
          class="group flex items-center gap-4 bg-lol-card rounded-xl p-4 border border-lol-border no-underline text-inherit transition-all duration-200 hover:border-lol-gold/50 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-lol-gold/5"
        >
          <!-- Avatar placeholder -->
          <div
            class="w-10 h-10 rounded-full bg-lol-gold/10 border border-lol-gold/20 flex items-center justify-center shrink-0 group-hover:border-lol-gold/40 transition-colors"
          >
            <User class="w-5 h-5 text-lol-gold/60" />
          </div>

          <div class="min-w-0">
            <p class="text-lol-text font-medium text-sm truncate">{{ summoner.riotId }}</p>
            <p class="text-lol-muted text-xs mt-0.5">
              Niv. {{ summoner.summonerLevel }} · {{ summoner.platform.toUpperCase() }}
            </p>
          </div>
        </RouterLink>
      </div>
    </template>
  </div>
</template>
