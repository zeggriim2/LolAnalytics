<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { summonerApi } from '@shared/api/summonerApi'
import type { Summoner } from '@shared/types'
import AlertMessage from '@shared/components/AlertMessage.vue'
import SummonerImportForm from '@admin/components/SummonerImportForm.vue'

const summoners = ref<Summoner[]>([])
const loading = ref(true)
const error = ref<string | null>(null)
const importMessage = ref<{ type: 'success' | 'error'; text: string } | null>(null)

function showMessage(type: 'success' | 'error', text: string) {
  importMessage.value = { type, text }
  setTimeout(() => {
    importMessage.value = null
  }, 4000)
}

async function loadSummoners() {
  const response = await summonerApi.getSummoners()
  summoners.value = response.data
}

async function onImportSuccess(message: string) {
  await loadSummoners()
  showMessage('success', message)
}

function onImportError(message: string) {
  showMessage('error', message)
}

function formatDate(dateString: string): string {
  return new Date(dateString).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  })
}

onMounted(async () => {
  try {
    await loadSummoners()
  } catch (e) {
    error.value = 'Failed to load summoners'
    console.error(e)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div>
    <header class="page-header">
      <h2>Summoners</h2>
    </header>

    <SummonerImportForm @success="onImportSuccess" @error="onImportError" />

    <AlertMessage v-if="importMessage" :type="importMessage.type" :message="importMessage.text" />

    <div v-if="loading" class="loading">Loading...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else class="card">
      <table class="table">
        <thead>
          <tr>
            <th>Riot ID</th>
            <th>Level</th>
            <th>Platform</th>
            <th>Last Updated</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="summoner in summoners" :key="summoner.puuid">
            <td>{{ summoner.riotId }}</td>
            <td>{{ summoner.summonerLevel }}</td>
            <td>{{ summoner.platform.toUpperCase() }}</td>
            <td>{{ formatDate(summoner.lastUpdatedAt) }}</td>
            <td>
              <RouterLink :to="`/summoners/${summoner.puuid}`" class="btn btn-primary">
                View
              </RouterLink>
            </td>
          </tr>
          <tr v-if="summoners.length === 0">
            <td colspan="5" style="text-align: center">No summoners found</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
