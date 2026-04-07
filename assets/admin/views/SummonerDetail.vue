<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, RouterLink } from 'vue-router'
import { ChevronLeft, Users, RefreshCw } from 'lucide-vue-next'
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
      <div class="flex items-center gap-3">
        <RouterLink
          to="/summoners"
          class="text-admin-text hover:text-admin-heading transition-colors"
          title="Retour"
        >
          <ChevronLeft class="w-5 h-5" />
        </RouterLink>
        <span class="text-admin-border">|</span>
        <Users class="w-4 h-4 text-admin-primary" />
        <h2 class="text-admin-heading font-semibold text-lg">
          {{ summoner?.riotId ?? 'Summoner Details' }}
        </h2>
      </div>
      <button
        v-if="summoner"
        class="admin-btn-secondary flex items-center gap-2"
        :disabled="syncLoading"
        @click="syncMatches"
      >
        <RefreshCw class="w-4 h-4" :class="{ 'animate-spin': syncLoading }" />
        {{ syncLoading ? 'Syncing…' : 'Sync Matches' }}
      </button>
    </template>

    <div v-if="loading" class="loading">Loading...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else-if="summoner">
      <div
        v-if="syncError"
        class="bg-red-500/10 border border-red-500/30 text-red-400 rounded-xl p-4 text-sm mb-4"
      >
        {{ syncError }}
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <StatCard :value="summoner.summonerLevel" label="Level" />
        <StatCard :value="summoner.platform.toUpperCase()" label="Platform" />
        <StatCard :value="summoner.profileIconId" label="Profile Icon ID" />
      </div>

      <div class="admin-card p-4 mb-6">
        <p class="text-admin-text text-sm">
          Dernière mise à jour :
          <span class="text-admin-heading">{{ formatDate(summoner.lastUpdatedAt) }}</span>
        </p>
        <p class="text-admin-text text-xs mt-1.5 font-mono break-all">{{ summoner.puuid }}</p>
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
                    :version="match.version"
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
