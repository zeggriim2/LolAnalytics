<script setup lang="ts">
import { onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { api } from '@shared/api/client'
import type { Match } from '@shared/types'
import { usePagination } from '@shared/composables/usePagination'
import PaginationBar from '@shared/components/PaginationBar.vue'
import AdminPageTemplate from '@admin/components/templates/AdminPageTemplate.vue'

const {
  items: matches,
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
} = usePagination<Match>((page, limit) => api.getMatches(page, limit))

onMounted(() => fetchPage(1))

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
  <AdminPageTemplate>
    <template #header>
      <h2>Matches</h2>
    </template>

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
                <RouterLink :to="`/matches/${match.id}`" class="btn btn-primary">View</RouterLink>
              </td>
            </tr>
            <tr v-if="matches.length === 0">
              <td colspan="8" class="text-center">No matches found</td>
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
  </AdminPageTemplate>
</template>
