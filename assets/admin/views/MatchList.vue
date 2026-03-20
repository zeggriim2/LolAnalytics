<script setup lang="ts">
import { onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { api } from '@shared/api/client'
import type { Match } from '@shared/types'
import { usePagination } from '@shared/composables/usePagination'
import { useTableControls } from '@shared/composables/useTableControls'
import PaginationBar from '@shared/components/PaginationBar.vue'
import SortableHeader from '@shared/components/SortableHeader.vue'
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

const ctrl = useTableControls(() => matches.value)

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
      <div class="mb-4">
        <input
          v-model="ctrl.search"
          class="table-search"
          placeholder="Rechercher dans la page courante..."
        />
      </div>

      <div
        class="transition-opacity duration-200"
        :class="{ 'opacity-50 pointer-events-none': loading }"
      >
        <table class="table">
          <thead>
            <tr>
              <SortableHeader
                label="Match ID"
                sort-key="id"
                :active-sort-key="ctrl.sortKey"
                :sort-dir="ctrl.sortDir"
                @sort="ctrl.toggleSort"
              />
              <SortableHeader
                label="Version"
                sort-key="version"
                :active-sort-key="ctrl.sortKey"
                :sort-dir="ctrl.sortDir"
                @sort="ctrl.toggleSort"
              />
              <SortableHeader
                label="Played At"
                sort-key="playedAt"
                :active-sort-key="ctrl.sortKey"
                :sort-dir="ctrl.sortDir"
                @sort="ctrl.toggleSort"
              />
              <SortableHeader
                label="Duration"
                sort-key="durationSeconds"
                :active-sort-key="ctrl.sortKey"
                :sort-dir="ctrl.sortDir"
                @sort="ctrl.toggleSort"
              />
              <SortableHeader
                label="Mode"
                sort-key="gameMode"
                :active-sort-key="ctrl.sortKey"
                :sort-dir="ctrl.sortDir"
                @sort="ctrl.toggleSort"
              />
              <SortableHeader
                label="Platform"
                sort-key="platform"
                :active-sort-key="ctrl.sortKey"
                :sort-dir="ctrl.sortDir"
                @sort="ctrl.toggleSort"
              />
              <SortableHeader
                label="Players"
                sort-key="participantsCount"
                :active-sort-key="ctrl.sortKey"
                :sort-dir="ctrl.sortDir"
                @sort="ctrl.toggleSort"
              />
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="match in ctrl.rows" :key="(match as Match).id">
              <td>{{ (match as Match).id }}</td>
              <td>{{ (match as Match).version }}</td>
              <td>{{ formatDate((match as Match).playedAt) }}</td>
              <td>{{ (match as Match).durationFormatted }}</td>
              <td>{{ (match as Match).gameMode }}</td>
              <td>{{ (match as Match).platform }}</td>
              <td>{{ (match as Match).participantsCount }}</td>
              <td>
                <RouterLink :to="`/matches/${(match as Match).id}`" class="btn btn-primary"
                  >View</RouterLink
                >
              </td>
            </tr>
            <tr v-if="ctrl.rows.length === 0">
              <td colspan="8" class="text-center text-lol-muted">Aucun résultat</td>
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
