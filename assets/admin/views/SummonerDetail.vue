<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, RouterLink } from 'vue-router'
import { api } from '@shared/api/client'
import type { Summoner } from '@shared/types'

const route = useRoute()
const summoner = ref<Summoner | null>(null)
const loading = ref(true)
const error = ref<string | null>(null)

onMounted(async () => {
  try {
    const puuid = route.params.puuid as string
    const response = await api.getSummoner(puuid)
    summoner.value = response.data
  } catch (e) {
    error.value = 'Failed to load summoner details'
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
  <div>
    <header class="page-header">
      <RouterLink to="/summoners" class="btn btn-secondary" style="margin-right: 1rem">
        &larr; Back
      </RouterLink>
      <h2 style="display: inline">Summoner Details</h2>
    </header>

    <div v-if="loading" class="loading">Loading...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else-if="summoner">
      <div class="card">
        <div class="card-header">
          <h3>{{ summoner.riotId }}</h3>
        </div>
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-value">{{ summoner.summonerLevel }}</div>
            <div class="stat-label">Level</div>
          </div>
          <div class="stat-card">
            <div class="stat-value">{{ summoner.platform.toUpperCase() }}</div>
            <div class="stat-label">Platform</div>
          </div>
          <div class="stat-card">
            <div class="stat-value">{{ summoner.profileIconId }}</div>
            <div class="stat-label">Profile Icon ID</div>
          </div>
        </div>
        <p style="color: var(--text-muted)">
          Last updated: {{ formatDate(summoner.lastUpdatedAt) }}
        </p>
        <p style="color: var(--text-muted); margin-top: 0.5rem">
          PUUID: <code>{{ summoner.puuid }}</code>
        </p>
      </div>
    </div>
  </div>
</template>
