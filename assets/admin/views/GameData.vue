<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { Database } from 'lucide-vue-next'
import { gameDataApi } from '@shared/api/gameDataApi'
import { useToast } from '@shared/composables/useToast'
import type { Queue, GameMap, GameMode, GameType, Version } from '@shared/types'
import { useTableControls } from '@shared/composables/useTableControls'
import SpinnerButton from '@shared/components/SpinnerButton.vue'
import SkeletonTable from '@shared/components/SkeletonTable.vue'
import SortableHeader from '@shared/components/SortableHeader.vue'
import GameDataTabItem from '@admin/components/molecules/GameDataTabItem.vue'
import AdminPageTemplate from '@admin/components/templates/AdminPageTemplate.vue'

const queues = ref<Queue[]>([])
const maps = ref<GameMap[]>([])
const gameModes = ref<GameMode[]>([])
const gameTypes = ref<GameType[]>([])
const versions = ref<Version[]>([])
const loading = ref(true)
const error = ref<string | null>(null)
const activeTab = ref('queues')

const syncingAll = ref(false)
const syncingType = ref<string | null>(null)
const toast = useToast()

const queuesCtrl = useTableControls(() => queues.value)
const mapsCtrl = useTableControls(() => maps.value)
const modesCtrl = useTableControls(() => gameModes.value)
const typesCtrl = useTableControls(() => gameTypes.value)
const versionsCtrl = useTableControls(() => versions.value)

const tabDefs = computed(() => [
  { key: 'queues', label: 'Queues', count: queues.value.length },
  { key: 'maps', label: 'Maps', count: maps.value.length },
  { key: 'modes', label: 'Game Modes', count: gameModes.value.length },
  { key: 'types', label: 'Game Types', count: gameTypes.value.length },
  { key: 'versions', label: 'Versions', count: versions.value.length },
])

async function loadAllData() {
  const [queuesRes, mapsRes, modesRes, typesRes, versionsRes] = await Promise.all([
    gameDataApi.getQueues(),
    gameDataApi.getMaps(),
    gameDataApi.getGameModes(),
    gameDataApi.getGameTypes(),
    gameDataApi.getVersions(),
  ])
  queues.value = queuesRes.data
  maps.value = mapsRes.data
  gameModes.value = modesRes.data
  gameTypes.value = typesRes.data
  versions.value = versionsRes.data
}

async function loadDataByType(type: string) {
  switch (type) {
    case 'queues':
      queues.value = (await gameDataApi.getQueues()).data
      break
    case 'maps':
      maps.value = (await gameDataApi.getMaps()).data
      break
    case 'game-modes':
      gameModes.value = (await gameDataApi.getGameModes()).data
      break
    case 'game-types':
      gameTypes.value = (await gameDataApi.getGameTypes()).data
      break
    case 'versions':
      versions.value = (await gameDataApi.getVersions()).data
      break
  }
}

async function syncAll() {
  syncingAll.value = true
  try {
    const res = await gameDataApi.syncAll()
    await loadAllData()
    toast.success(res.message)
  } catch (e) {
    toast.error(e instanceof Error ? e.message : 'Sync failed')
  } finally {
    syncingAll.value = false
  }
}

async function syncType(type: string) {
  syncingType.value = type
  try {
    const res = await gameDataApi.sync(type)
    await loadDataByType(type)
    toast.success(res.message)
  } catch (e) {
    toast.error(e instanceof Error ? e.message : 'Sync failed')
  } finally {
    syncingType.value = null
  }
}

const isSyncing = (type?: string) => {
  if (syncingAll.value) return true
  if (type) return syncingType.value === type
  return syncingType.value !== null
}

onMounted(async () => {
  try {
    await loadAllData()
  } catch (e) {
    error.value = 'Failed to load game data'
    console.error(e)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <AdminPageTemplate>
    <template #header>
      <div class="flex items-center gap-2">
        <Database class="w-5 h-5 text-admin-primary" />
        <h2 class="text-admin-heading font-semibold text-lg">Game Data</h2>
      </div>
      <SpinnerButton
        :loading="syncingAll"
        :disabled="isSyncing()"
        label="Sync All"
        loading-label="Syncing..."
        @click="syncAll"
      />
    </template>

    <div v-if="error" class="error">{{ error }}</div>
    <div v-if="loading" class="admin-card p-6 mb-6">
      <SkeletonTable :rows="6" :cols="3" />
    </div>
    <div v-else class="bg-lol-card rounded-lg border border-lol-border mb-6">
      <div class="tab-bar px-2">
        <GameDataTabItem
          v-for="tab in tabDefs"
          :key="tab.key"
          :label="tab.label"
          :count="tab.count"
          :active="activeTab === tab.key"
          @select="activeTab = tab.key"
        />
      </div>

      <div class="p-6">
        <!-- Queues -->
        <div v-if="activeTab === 'queues'">
          <div class="flex items-center gap-3 mb-4">
            <input
              v-model="queuesCtrl.search"
              class="table-search flex-1"
              placeholder="Rechercher dans les queues..."
            />
            <SpinnerButton
              :loading="syncingType === 'queues'"
              :disabled="isSyncing('queues')"
              label="Sync"
              loading-label="Syncing..."
              @click="syncType('queues')"
            />
          </div>
          <table class="table">
            <thead>
              <tr>
                <SortableHeader
                  label="Queue ID"
                  sort-key="queueId"
                  :active-sort-key="queuesCtrl.sortKey"
                  :sort-dir="queuesCtrl.sortDir"
                  @sort="queuesCtrl.toggleSort"
                />
                <SortableHeader
                  label="Map"
                  sort-key="map"
                  :active-sort-key="queuesCtrl.sortKey"
                  :sort-dir="queuesCtrl.sortDir"
                  @sort="queuesCtrl.toggleSort"
                />
                <SortableHeader
                  label="Description"
                  sort-key="description"
                  :active-sort-key="queuesCtrl.sortKey"
                  :sort-dir="queuesCtrl.sortDir"
                  @sort="queuesCtrl.toggleSort"
                />
              </tr>
            </thead>
            <tbody>
              <tr v-for="queue in queuesCtrl.rows" :key="(queue as Queue).queueId">
                <td>{{ (queue as Queue).queueId }}</td>
                <td>{{ (queue as Queue).map }}</td>
                <td>{{ (queue as Queue).description || '-' }}</td>
              </tr>
              <tr v-if="queuesCtrl.rows.length === 0">
                <td colspan="3" class="text-center text-lol-muted">Aucun résultat</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Maps -->
        <div v-if="activeTab === 'maps'">
          <div class="flex items-center gap-3 mb-4">
            <input
              v-model="mapsCtrl.search"
              class="table-search flex-1"
              placeholder="Rechercher dans les maps..."
            />
            <SpinnerButton
              :loading="syncingType === 'maps'"
              :disabled="isSyncing('maps')"
              label="Sync"
              loading-label="Syncing..."
              @click="syncType('maps')"
            />
          </div>
          <table class="table">
            <thead>
              <tr>
                <SortableHeader
                  label="Map ID"
                  sort-key="mapId"
                  :active-sort-key="mapsCtrl.sortKey"
                  :sort-dir="mapsCtrl.sortDir"
                  @sort="mapsCtrl.toggleSort"
                />
                <SortableHeader
                  label="Map Name"
                  sort-key="mapName"
                  :active-sort-key="mapsCtrl.sortKey"
                  :sort-dir="mapsCtrl.sortDir"
                  @sort="mapsCtrl.toggleSort"
                />
                <SortableHeader
                  label="Notes"
                  sort-key="notes"
                  :active-sort-key="mapsCtrl.sortKey"
                  :sort-dir="mapsCtrl.sortDir"
                  @sort="mapsCtrl.toggleSort"
                />
              </tr>
            </thead>
            <tbody>
              <tr v-for="map in mapsCtrl.rows" :key="(map as GameMap).mapId">
                <td>{{ (map as GameMap).mapId }}</td>
                <td>{{ (map as GameMap).mapName }}</td>
                <td>{{ (map as GameMap).notes || '-' }}</td>
              </tr>
              <tr v-if="mapsCtrl.rows.length === 0">
                <td colspan="3" class="text-center text-lol-muted">Aucun résultat</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Game Modes -->
        <div v-if="activeTab === 'modes'">
          <div class="flex items-center gap-3 mb-4">
            <input
              v-model="modesCtrl.search"
              class="table-search flex-1"
              placeholder="Rechercher dans les modes..."
            />
            <SpinnerButton
              :loading="syncingType === 'game-modes'"
              :disabled="isSyncing('game-modes')"
              label="Sync"
              loading-label="Syncing..."
              @click="syncType('game-modes')"
            />
          </div>
          <table class="table">
            <thead>
              <tr>
                <SortableHeader
                  label="Game Mode"
                  sort-key="gameMode"
                  :active-sort-key="modesCtrl.sortKey"
                  :sort-dir="modesCtrl.sortDir"
                  @sort="modesCtrl.toggleSort"
                />
                <SortableHeader
                  label="Description"
                  sort-key="description"
                  :active-sort-key="modesCtrl.sortKey"
                  :sort-dir="modesCtrl.sortDir"
                  @sort="modesCtrl.toggleSort"
                />
              </tr>
            </thead>
            <tbody>
              <tr v-for="mode in modesCtrl.rows" :key="(mode as GameMode).gameMode">
                <td>{{ (mode as GameMode).gameMode }}</td>
                <td>{{ (mode as GameMode).description }}</td>
              </tr>
              <tr v-if="modesCtrl.rows.length === 0">
                <td colspan="2" class="text-center text-lol-muted">Aucun résultat</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Game Types -->
        <div v-if="activeTab === 'types'">
          <div class="flex items-center gap-3 mb-4">
            <input
              v-model="typesCtrl.search"
              class="table-search flex-1"
              placeholder="Rechercher dans les types..."
            />
            <SpinnerButton
              :loading="syncingType === 'game-types'"
              :disabled="isSyncing('game-types')"
              label="Sync"
              loading-label="Syncing..."
              @click="syncType('game-types')"
            />
          </div>
          <table class="table">
            <thead>
              <tr>
                <SortableHeader
                  label="Game Type"
                  sort-key="gameType"
                  :active-sort-key="typesCtrl.sortKey"
                  :sort-dir="typesCtrl.sortDir"
                  @sort="typesCtrl.toggleSort"
                />
                <SortableHeader
                  label="Description"
                  sort-key="description"
                  :active-sort-key="typesCtrl.sortKey"
                  :sort-dir="typesCtrl.sortDir"
                  @sort="typesCtrl.toggleSort"
                />
              </tr>
            </thead>
            <tbody>
              <tr v-for="type in typesCtrl.rows" :key="(type as GameType).gameType">
                <td>{{ (type as GameType).gameType }}</td>
                <td>{{ (type as GameType).description }}</td>
              </tr>
              <tr v-if="typesCtrl.rows.length === 0">
                <td colspan="2" class="text-center text-lol-muted">Aucun résultat</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Versions -->
        <div v-if="activeTab === 'versions'">
          <div class="flex items-center gap-3 mb-4">
            <input
              v-model="versionsCtrl.search"
              class="table-search flex-1"
              placeholder="Rechercher une version..."
            />
            <SpinnerButton
              :loading="syncingType === 'versions'"
              :disabled="isSyncing('versions')"
              label="Sync"
              loading-label="Syncing..."
              @click="syncType('versions')"
            />
          </div>
          <table class="table">
            <thead>
              <tr>
                <SortableHeader
                  label="Version"
                  sort-key="version"
                  :active-sort-key="versionsCtrl.sortKey"
                  :sort-dir="versionsCtrl.sortDir"
                  @sort="versionsCtrl.toggleSort"
                />
              </tr>
            </thead>
            <tbody>
              <tr v-for="version in versionsCtrl.rows" :key="(version as Version).version">
                <td>{{ (version as Version).version }}</td>
              </tr>
              <tr v-if="versionsCtrl.rows.length === 0">
                <td colspan="1" class="text-center text-lol-muted">Aucun résultat</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AdminPageTemplate>
</template>
