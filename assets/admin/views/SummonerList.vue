<script setup lang="ts">
import { onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { summonerApi } from '@shared/api/summonerApi'
import type { Summoner } from '@shared/types'
import { usePagination } from '@shared/composables/usePagination'
import PaginationBar from '@shared/components/PaginationBar.vue'
import AlertMessage from '@shared/components/AlertMessage.vue'
import SummonerImportForm from '@admin/components/SummonerImportForm.vue'
import { ref } from 'vue'

const {
  items: summoners,
  initialLoading,
  loading,
  error,
  meta,
  hasPrevious,
  hasNext,
  fetchPage,
  goToPage,
  nextPage,
  previousPage,
} = usePagination<Summoner>((page, limit) => summonerApi.getSummoners(page, limit))

const importMessage = ref<{ type: 'success' | 'error'; text: string } | null>(null)

function showMessage(type: 'success' | 'error', text: string) {
  importMessage.value = { type, text }
  setTimeout(() => {
    importMessage.value = null
  }, 4000)
}

async function onImportSuccess(message: string) {
  await fetchPage(meta.value.page)
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

onMounted(() => fetchPage(1))
</script>

<template>
  <div>
    <header class="page-header">
      <h2>Summoners</h2>
    </header>

    <SummonerImportForm @success="onImportSuccess" @error="onImportError" />

    <AlertMessage v-if="importMessage" :type="importMessage.type" :message="importMessage.text" />

    <div v-if="initialLoading" class="loading">Loading...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else class="card">
      <div
        class="transition-opacity duration-200"
        :class="{ 'opacity-50 pointer-events-none': loading }"
      >
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
              <td colspan="5" class="text-center">No summoners found</td>
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
  </div>
</template>
