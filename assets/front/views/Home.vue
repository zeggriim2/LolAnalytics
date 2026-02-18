<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { api } from '@shared/api/client'
import type { Match } from '@shared/types'

const router = useRouter()
const recentMatches = ref<Match[]>([])
const loading = ref(true)
const searchQuery = ref('')

onMounted(async () => {
  try {
    const response = await api.getMatches()
    recentMatches.value = response.data.slice(0, 6)
  } catch (e) {
    console.error('Failed to load matches:', e)
  } finally {
    loading.value = false
  }
})

function onSearch() {
  if (searchQuery.value.trim()) {
    router.push({ name: 'summoner-search', query: { q: searchQuery.value.trim() } })
  }
}

function formatDate(dateString: string): string {
  return new Date(dateString).toLocaleDateString('fr-FR', {
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}
</script>

<template>
  <div>
    <section class="text-center py-16">
      <div class="container">
        <h2 class="text-4xl mb-4 text-lol-gold">Analyze Your League of Legends Matches</h2>
        <p class="text-xl text-lol-muted mb-8">
          Search for a summoner to view their match history and statistics
        </p>
        <form class="search-box" @submit.prevent="onSearch">
          <input
            v-model="searchQuery"
            type="text"
            class="search-input"
            placeholder="Enter Summoner Name#TAG (e.g., Faker#KR1)"
            @keyup.enter="onSearch"
          />
        </form>
      </div>
    </section>

    <section class="py-12">
      <div class="container">
        <h3 class="text-2xl mb-6 text-lol-gold">Recent Matches</h3>
        <div v-if="loading" class="loading">Loading...</div>
        <div v-else class="grid grid-cols-[repeat(auto-fill,minmax(300px,1fr))] gap-4">
          <RouterLink
            v-for="match in recentMatches"
            :key="match.id"
            :to="`/matches/${match.id}`"
            class="bg-lol-card rounded-lg p-6 border border-lol-border no-underline text-inherit transition-transform duration-200 hover:-translate-y-0.5 hover:shadow-lg"
          >
            <h3 class="text-base mb-2">{{ match.gameMode }}</h3>
            <p class="text-lol-muted text-sm">
              {{ formatDate(match.playedAt) }} · {{ match.durationFormatted }} ·
              {{ match.participantsCount }} players
            </p>
          </RouterLink>
          <div v-if="recentMatches.length === 0" class="loading">No matches found</div>
        </div>
      </div>
    </section>
  </div>
</template>
