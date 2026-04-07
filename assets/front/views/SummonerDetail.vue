<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, RouterLink } from 'vue-router'
import { api } from '@shared/api/client'
import { matchApi } from '@shared/api/matchApi'
import { usePagination } from '@shared/composables/usePagination'
import PaginationBar from '@shared/components/PaginationBar.vue'
import SummonerMatchCharts from '@shared/components/SummonerMatchCharts.vue'
import ChampionIcon from '@shared/components/ChampionIcon.vue'
import type { Summoner, Match } from '@shared/types'
import { ChevronLeft, User, Star, Globe } from 'lucide-vue-next'

const route = useRoute()
const summoner = ref<Summoner | null>(null)
const loading = ref(true)
const error = ref<string | null>(null)

const puuid = route.params.puuid as string

const {
  items: matches,
  initialLoading: matchesInitialLoading,
  loading: matchesLoading,
  error: matchesError,
  meta,
  hasPrevious,
  hasNext,
  fetchPage,
  goToPage,
  nextPage,
  previousPage,
} = usePagination<Match>((page, limit) => matchApi.getMatchesBySummoner(puuid, page, limit), 10)

onMounted(async () => {
  try {
    const response = await api.getSummoner(puuid)
    summoner.value = response.data
    fetchPage(1)
  } catch (e) {
    error.value = 'Impossible de charger le profil'
    console.error(e)
  } finally {
    loading.value = false
  }
})

function findParticipant(match: Match) {
  return match.participants?.find((p) => p.summonerId === puuid)
}

function formatDate(dateString: string): string {
  return new Date(dateString).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}
</script>

<template>
  <div class="container py-8">
    <!-- Back -->
    <RouterLink
      to="/summoners"
      class="inline-flex items-center gap-1.5 text-lol-muted text-sm no-underline hover:text-lol-text transition-colors mb-6"
    >
      <ChevronLeft class="w-4 h-4" />
      Retour aux invocateurs
    </RouterLink>

    <div v-if="loading" class="loading">Chargement…</div>
    <div v-else-if="error" class="error">{{ error }}</div>

    <div v-else-if="summoner">
      <!-- Profile card -->
      <div
        class="bg-lol-card border border-lol-border rounded-2xl p-6 mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-6"
      >
        <!-- Avatar -->
        <div
          class="w-16 h-16 rounded-full bg-lol-gold/10 border-2 border-lol-gold/30 flex items-center justify-center shrink-0"
        >
          <User class="w-8 h-8 text-lol-gold/60" />
        </div>

        <div class="flex-1 min-w-0">
          <h2 class="text-2xl font-bold text-lol-text">{{ summoner.riotId }}</h2>
          <p class="text-lol-muted text-sm mt-1">
            Dernière mise à jour : {{ formatDate(summoner.lastUpdatedAt) }}
          </p>
        </div>

        <!-- Stats inline -->
        <div class="flex flex-wrap gap-6 shrink-0">
          <div class="text-center">
            <div class="flex items-center gap-1.5 text-lol-gold font-bold text-xl justify-center">
              <Star class="w-4 h-4" />
              {{ summoner.summonerLevel }}
            </div>
            <p class="text-lol-muted text-xs mt-0.5">Niveau</p>
          </div>
          <div class="text-center">
            <div class="flex items-center gap-1.5 text-lol-text font-bold text-xl justify-center">
              <Globe class="w-4 h-4 text-lol-muted" />
              {{ summoner.platform.toUpperCase() }}
            </div>
            <p class="text-lol-muted text-xs mt-0.5">Serveur</p>
          </div>
        </div>
      </div>

      <!-- Charts & Match History -->
      <div v-if="matchesInitialLoading" class="loading">Chargement des parties…</div>

      <template v-else-if="matches.length > 0">
        <SummonerMatchCharts :matches="matches" :summoner-puuid="puuid" />

        <!-- Match history -->
        <div class="card" :class="{ 'opacity-50 pointer-events-none': matchesLoading }">
          <div class="card-header">
            <h3 class="font-semibold text-lol-text">Historique des parties</h3>
            <span class="text-lol-muted text-sm">{{ meta.total }} parties</span>
          </div>

          <div class="overflow-x-auto">
            <table class="table">
              <thead>
                <tr>
                  <th>Mode</th>
                  <th>Champion</th>
                  <th>K/D/A</th>
                  <th>Résultat</th>
                  <th>Date</th>
                  <th>Durée</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="match in matches" :key="match.id">
                  <td class="text-lol-muted text-xs">{{ match.gameMode }}</td>
                  <td>
                    <ChampionIcon
                      v-if="findParticipant(match)?.championId"
                      :champion-id="findParticipant(match)!.championId"
                      :version="match.version"
                      :size="32"
                    />
                    <span v-else class="text-lol-muted text-xs">—</span>
                  </td>
                  <td class="font-mono text-sm">
                    {{ findParticipant(match)?.kills }}/{{ findParticipant(match)?.deaths }}/{{
                      findParticipant(match)?.assists
                    }}
                  </td>
                  <td>
                    <span
                      class="badge text-white text-xs"
                      :class="findParticipant(match)?.win ? 'bg-lol-win' : 'bg-lol-loss'"
                    >
                      {{ findParticipant(match)?.win ? 'Victoire' : 'Défaite' }}
                    </span>
                  </td>
                  <td class="text-lol-muted text-xs whitespace-nowrap">
                    {{ formatDate(match.playedAt) }}
                  </td>
                  <td class="text-lol-muted text-xs">{{ match.durationFormatted }}</td>
                  <td>
                    <RouterLink
                      :to="`/matches/${match.id}`"
                      class="btn btn-primary text-xs px-3 py-1"
                    >
                      Voir
                    </RouterLink>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <PaginationBar
            :meta="meta"
            :has-previous="hasPrevious"
            :has-next="hasNext"
            @previous="previousPage"
            @next="nextPage"
            @go-to-page="goToPage"
          />
        </div>
      </template>

      <div v-else-if="matchesError" class="error">{{ matchesError }}</div>
      <div v-else class="loading">Aucune partie enregistrée</div>
    </div>
  </div>
</template>
