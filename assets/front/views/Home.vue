<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { api } from '@shared/api/client'
import type { Match } from '@shared/types'
import { Search, Swords, Clock, Users } from 'lucide-vue-next'

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
    <!-- Hero Section -->
    <section class="relative py-20 overflow-hidden">
      <!-- Background gradient -->
      <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div
          class="absolute inset-0 bg-gradient-to-b from-lol-gold/5 via-transparent to-transparent"
        />
        <div
          class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-lol-gold/8 rounded-full blur-3xl"
        />
      </div>

      <div class="container relative">
        <div class="text-center max-w-2xl mx-auto mb-10">
          <div
            class="inline-flex items-center gap-2 bg-lol-gold/10 border border-lol-gold/30 rounded-full px-4 py-1.5 text-lol-gold text-xs font-medium mb-6"
          >
            <Swords class="w-3.5 h-3.5" />
            League of Legends Analytics
          </div>
          <h2 class="text-4xl lg:text-5xl font-bold text-lol-text mb-4 leading-tight">
            Analysez vos <span class="text-lol-gold">parties</span>
          </h2>
          <p class="text-lol-muted text-lg">
            Recherchez un invocateur pour consulter son historique de matchs et ses statistiques
            détaillées.
          </p>
        </div>

        <!-- Search -->
        <form class="search-box" @submit.prevent="onSearch">
          <div class="relative">
            <Search
              class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-lol-muted pointer-events-none"
            />
            <input
              v-model="searchQuery"
              type="text"
              class="search-input pl-12 pr-4"
              placeholder="Faker#KR1, T1 Faker#KR1…"
            />
          </div>
        </form>
      </div>
    </section>

    <!-- Recent Matches -->
    <section class="py-10 border-t border-lol-border/30">
      <div class="container">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-semibold text-lol-text">Parties récentes</h3>
          <RouterLink to="/summoners" class="text-lol-gold text-sm no-underline hover:underline">
            Voir les invocateurs →
          </RouterLink>
        </div>

        <div v-if="loading" class="loading">Chargement…</div>

        <div
          v-else-if="recentMatches.length > 0"
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"
        >
          <RouterLink
            v-for="match in recentMatches"
            :key="match.id"
            :to="`/matches/${match.id}`"
            class="group block bg-lol-card rounded-xl p-5 border border-lol-border no-underline transition-all duration-200 hover:border-lol-gold/50 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-lol-gold/5"
          >
            <div class="flex items-center justify-between mb-3">
              <span
                class="inline-flex items-center gap-1.5 bg-lol-bg px-2.5 py-1 rounded-full text-xs font-medium text-lol-muted"
              >
                <Swords class="w-3 h-3" />
                {{ match.gameMode }}
              </span>
              <span class="text-lol-border text-xs">{{ match.platform.toUpperCase() }}</span>
            </div>

            <div class="flex items-center gap-4 text-sm text-lol-muted mt-2">
              <span class="flex items-center gap-1">
                <Clock class="w-3.5 h-3.5" />
                {{ match.durationFormatted }}
              </span>
              <span class="flex items-center gap-1">
                <Users class="w-3.5 h-3.5" />
                {{ match.participantsCount }} joueurs
              </span>
            </div>

            <p class="text-lol-muted/60 text-xs mt-3">{{ formatDate(match.playedAt) }}</p>
          </RouterLink>
        </div>

        <div v-else class="loading">Aucune partie trouvée</div>
      </div>
    </section>
  </div>
</template>
