<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { api } from '@shared/api/client'
import type { Match } from '@shared/types'

const matches = ref<Match[]>([])
const loading = ref(true)
const error = ref<string | null>(null)

onMounted(async () => {
  try {
    const response = await api.getMatches()
    matches.value = response.data
  } catch (e) {
    error.value = 'Failed to load matches'
    console.error(e)
  } finally {
    loading.value = false
  }
})

function formatDate(dateString: string): string {
  return new Date(dateString).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}
</script>

<template>
  <div>
    <header class="page-header">
      <h2>Matches</h2>
    </header>

    <div v-if="loading" class="loading">Loading...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else class="card">
      <table class="table">
        <thead>
          <tr>
            <th>Match ID</th>
            <th>Version</th>
            <th>Played At</th>
            <th>Duration</th>
            <th>Mode</th>
            <th>Platform</th>
            <th>Players</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="match in matches" :key="match.id">
            <td>{{ match.id }}</td>
            <td>{{ match.version }}</td>
            <td>{{ formatDate(match.playedAt) }}</td>
            <td>{{ match.durationFormatted }}</td>
            <td>{{ match.gameMode }}</td>
            <td>{{ match.platform }}</td>
            <td>{{ match.participantsCount }}</td>
            <td>
              <RouterLink :to="`/matches/${match.id}`" class="btn btn-primary"> View </RouterLink>
            </td>
          </tr>
          <tr v-if="matches.length === 0">
            <td colspan="7" style="text-align: center">No matches found</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
