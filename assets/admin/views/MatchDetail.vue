<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useRoute, RouterLink } from 'vue-router'
import { api } from '@shared/api/client'
import type { Match } from '@shared/types'
import StatCard from '@admin/components/atoms/StatCard.vue'
import MatchParticipantTable from '@admin/components/organisms/MatchParticipantTable.vue'
import AdminPageTemplate from '@admin/components/templates/AdminPageTemplate.vue'

const route = useRoute()
const match = ref<Match | null>(null)
const loading = ref(true)
const error = ref<string | null>(null)

const winners = computed(() => match.value?.participants?.filter((p) => p.win) || [])
const losers = computed(() => match.value?.participants?.filter((p) => !p.win) || [])

onMounted(async () => {
  try {
    const matchId = route.params.matchId as string
    const response = await api.getMatch(matchId)
    match.value = response.data
  } catch (e) {
    error.value = 'Failed to load match details'
    console.error(e)
  } finally {
    loading.value = false
  }
})

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
      <RouterLink to="/matches" class="btn btn-secondary mr-4">&larr; Back</RouterLink>
      <h2 class="inline">Match Details</h2>
    </template>

    <div v-if="loading" class="loading">Loading...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else-if="match">
      <div class="stats-grid">
        <StatCard :value="match.version" label="Patch" />
        <StatCard :value="match.durationFormatted" label="Duration" />
        <StatCard :value="match.gameMode" label="Game Mode" />
        <StatCard :value="match.platform.toUpperCase()" label="Platform" />
        <StatCard :value="formatDate(match.playedAt)" label="Played At" />
      </div>

      <MatchParticipantTable
        :participants="winners"
        :version="match.version"
        title="Winners"
        :win="true"
      />
      <MatchParticipantTable
        :participants="losers"
        :version="match.version"
        title="Losers"
        :win="false"
      />
    </div>
  </AdminPageTemplate>
</template>
