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
import StatCard from '@admin/components/atoms/StatCard.vue'
import WinBadge from '@admin/components/atoms/WinBadge.vue'
import AdminPageTemplate from '@admin/components/templates/AdminPageTemplate.vue'

const route = useRoute()
const summoner = ref<Summoner | null>(null)
const loading = ref(true)
const error = ref<string | null>(null)
const syncLoading = ref(false)
const syncError = ref<string | null>(null)

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
    error.value = 'Failed to load summoner details'
    console.error(e)
  } finally {
    loading.value = false
  }
})

async function syncMatches() {
  syncLoading.value = true
  syncError.value = null
  try {
    await matchApi.syncMatchesBySummoner(puuid)
    fetchPage(1)
  } catch (e) {
    syncError.value = 'Failed to sync matches'
    console.error(e)
  } finally {
    syncLoading.value = false
  }
}

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
  <AdminPageTemplate>
    <template #header>
      <RouterLink to="/summoners" class="btn btn-secondary mr-4">&larr; Back</RouterLink>
      <h2 class="inline">Summoner Details</h2>
    </template>

    <div v-if="loading" class="loading">Loading...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else-if="summoner">
      <div class="card">
        <div class="card-header">
          <h3>{{ summoner.riotId }}</h3>
          <div class="flex items-center gap-2">
            <button class="btn btn-primary" :disabled="syncLoading" @click="syncMatches">
              <span v-if="syncLoading">Syncing...</span>
              <span v-else>Sync Matches</span>
            </button>
          </div>
        </div>
        <div v-if="syncError" class="error">{{ syncError }}</div>
        <div class="stats-grid">
          <StatCard :value="summoner.summonerLevel" label="Level" />
          <StatCard :value="summoner.platform.toUpperCase()" label="Platform" />
          <StatCard :value="summoner.profileIconId" label="Profile Icon ID" />
        </div>
        <p class="text-lol-muted">Last updated: {{ formatDate(summoner.lastUpdatedAt) }}</p>
        <p class="text-lol-muted mt-2">
          PUUID: <code>{{ summoner.puuid }}</code>
        </p>
      </div>

      <div v-if="matchesInitialLoading" class="loading">Loading matches...</div>
      <template v-else-if="matches.length > 0">
        <SummonerMatchCharts :matches="matches" :summoner-puuid="puuid" />

        <div class="card" :class="{ 'opacity-50': matchesLoading }">
          <div class="card-header">
            <h3>Match History</h3>
          </div>
          <table class="table">
            <thead>
              <tr>
                <th>Mode</th>
                <th>Champion</th>
                <th>K/D/A</th>
                <th>Result</th>
                <th>Date</th>
                <th>Duration</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="match in matches" :key="match.id">
                <td>{{ match.gameMode }}</td>
                <td>
                  <ChampionIcon
                    v-if="findParticipant(match)?.championId"
                    :champion-id="findParticipant(match)!.championId"
                  />
                </td>
                <td>
                  {{ findParticipant(match)?.kills }}/{{ findParticipant(match)?.deaths }}/{{
                    findParticipant(match)?.assists
                  }}
                </td>
                <td>
                  <WinBadge :win="findParticipant(match)?.win ?? false" />
                </td>
                <td>{{ formatDate(match.playedAt) }}</td>
                <td>{{ match.durationFormatted }}</td>
                <td>
                  <RouterLink :to="`/matches/${match.id}`" class="btn btn-primary btn-sm">
                    View
                  </RouterLink>
                </td>
              </tr>
            </tbody>
          </table>
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
    </div>
  </AdminPageTemplate>
</template>
