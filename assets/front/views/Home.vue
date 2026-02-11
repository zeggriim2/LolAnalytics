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
    <section class="hero">
      <div class="container">
        <h2>Analyze Your League of Legends Matches</h2>
        <p>Search for a summoner to view their match history and statistics</p>
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

    <section class="section">
      <div class="container">
        <h3 class="section-title">Recent Matches</h3>
        <div v-if="loading" class="loading">Loading...</div>
        <div v-else class="matches-grid">
          <RouterLink
            v-for="match in recentMatches"
            :key="match.id"
            :to="`/matches/${match.id}`"
            class="match-card"
            style="text-decoration: none; color: inherit"
          >
            <h3>{{ match.gameMode }}</h3>
            <p class="meta">
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

<style scoped>
.match-card .meta {
  color: var(--text-muted);
  font-size: 0.875rem;
}

.hero {
  padding: 4rem 0;
  text-align: center;

  h2 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    color: var(--accent-color);
  }

  p {
    font-size: 1.25rem;
    color: var(--text-muted);
    margin-bottom: 2rem;
  }
}
</style>
