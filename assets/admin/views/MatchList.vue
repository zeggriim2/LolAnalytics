<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { RouterLink } from 'vue-router'
import { matchApi } from '@shared/api/matchApi'
import { api } from '@shared/api/client'
import type { Match, Platform, GameMode, Version } from '@shared/types'
import type { MatchFilters } from '@shared/api/matchApi'
import { usePagination } from '@shared/composables/usePagination'
import { useTableControls } from '@shared/composables/useTableControls'
import PaginationBar from '@shared/components/PaginationBar.vue'
import SortableHeader from '@shared/components/SortableHeader.vue'
import AdminPageTemplate from '@admin/components/templates/AdminPageTemplate.vue'

const platforms = ref<Platform[]>([])
const gameModes = ref<GameMode[]>([])
const versions = ref<Version[]>([])

const filters = ref<MatchFilters>({
  platform: '',
  gameMode: '',
  version: '',
  dateFrom: '',
  dateTo: '',
})

function activeFilters(): MatchFilters {
  const f = filters.value
  return {
    platform: f.platform || undefined,
    gameMode: f.gameMode || undefined,
    version: f.version || undefined,
    dateFrom: f.dateFrom || undefined,
    dateTo: f.dateTo || undefined,
  }
}

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
} = usePagination<Match>((page, limit) => matchApi.getMatches(page, limit, activeFilters()))

const ctrl = useTableControls(() => matches.value)

onMounted(async () => {
  const [platformsRes, gameModesRes, versionsRes] = await Promise.all([
    api.getPlatforms(),
    api.getGameModes(),
    api.getVersions(),
  ])
  platforms.value = platformsRes.data
  gameModes.value = gameModesRes.data
  versions.value = versionsRes.data

  await fetchPage(1)
})

watch(filters, () => fetchPage(1), { deep: true })

function resetFilters() {
  filters.value = { platform: '', gameMode: '', version: '', dateFrom: '', dateTo: '' }
}

const hasActiveFilters = () => Object.values(filters.value).some((v) => v !== '')

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
      <div class="mb-4 flex flex-wrap gap-2 items-end">
        <input
          v-model="ctrl.search"
          class="table-search"
          placeholder="Rechercher dans la page courante..."
        />

        <select v-model="filters.platform" class="table-filter-select">
          <option value="">Toutes les plateformes</option>
          <option v-for="p in platforms" :key="p.value" :value="p.value">{{ p.label }}</option>
        </select>

        <select v-model="filters.gameMode" class="table-filter-select">
          <option value="">Tous les modes</option>
          <option v-for="m in gameModes" :key="m.gameMode" :value="m.gameMode">
            {{ m.gameMode }}
          </option>
        </select>

        <select v-model="filters.version" class="table-filter-select">
          <option value="">Toutes les versions</option>
          <option v-for="v in versions" :key="v.version" :value="v.version">
            {{ v.version }}
          </option>
        </select>

        <div class="flex items-center gap-1">
          <input
            v-model="filters.dateFrom"
            type="date"
            class="table-filter-input"
            title="Date début"
          />
          <span class="text-lol-muted text-sm">→</span>
          <input v-model="filters.dateTo" type="date" class="table-filter-input" title="Date fin" />
        </div>

        <button v-if="hasActiveFilters()" class="btn btn-secondary text-sm" @click="resetFilters">
          Réinitialiser
        </button>
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
