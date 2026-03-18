<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { gameDataApi } from '@shared/api/gameDataApi'
import type { Queue, GameMap, GameMode, GameType, Version } from '@shared/types'
import AlertMessage from '@shared/components/AlertMessage.vue'
import SpinnerButton from '@shared/components/SpinnerButton.vue'
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
const syncMessage = ref<{ type: 'success' | 'error'; text: string } | null>(null)

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

function showMessage(type: 'success' | 'error', text: string) {
  syncMessage.value = { type, text }
  setTimeout(() => {
    syncMessage.value = null
  }, 4000)
}

async function syncAll() {
  syncingAll.value = true
  syncMessage.value = null
  try {
    const res = await gameDataApi.syncAll()
    await loadAllData()
    showMessage('success', res.message)
  } catch (e) {
    showMessage('error', e instanceof Error ? e.message : 'Sync failed')
  } finally {
    syncingAll.value = false
  }
}

async function syncType(type: string) {
  syncingType.value = type
  syncMessage.value = null
  try {
    const res = await gameDataApi.sync(type)
    await loadDataByType(type)
    showMessage('success', res.message)
  } catch (e) {
    showMessage('error', e instanceof Error ? e.message : 'Sync failed')
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
      <h2>Game Data</h2>
      <SpinnerButton
        :loading="syncingAll"
        :disabled="isSyncing()"
        label="Sync All"
        loading-label="Syncing..."
        @click="syncAll"
      />
    </template>

    <AlertMessage v-if="syncMessage" :type="syncMessage.type" :message="syncMessage.text" />

    <div v-if="loading" class="loading">Loading...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else>
      <div class="mb-4">
        <div class="flex flex-wrap gap-1">
          <GameDataTabItem
            label="Queues"
            :count="queues.length"
            :active="activeTab === 'queues'"
            :syncing="syncingType === 'queues'"
            :disabled="isSyncing('queues')"
            @select="activeTab = 'queues'"
            @sync="syncType('queues')"
          />
          <GameDataTabItem
            label="Maps"
            :count="maps.length"
            :active="activeTab === 'maps'"
            :syncing="syncingType === 'maps'"
            :disabled="isSyncing('maps')"
            @select="activeTab = 'maps'"
            @sync="syncType('maps')"
          />
          <GameDataTabItem
            label="Game Modes"
            :count="gameModes.length"
            :active="activeTab === 'modes'"
            :syncing="syncingType === 'game-modes'"
            :disabled="isSyncing('game-modes')"
            @select="activeTab = 'modes'"
            @sync="syncType('game-modes')"
          />
          <GameDataTabItem
            label="Game Types"
            :count="gameTypes.length"
            :active="activeTab === 'types'"
            :syncing="syncingType === 'game-types'"
            :disabled="isSyncing('game-types')"
            @select="activeTab = 'types'"
            @sync="syncType('game-types')"
          />
          <GameDataTabItem
            label="Version"
            :count="versions.length"
            :active="activeTab === 'versions'"
            :syncing="syncingType === 'versions'"
            :disabled="isSyncing('versions')"
            @select="activeTab = 'versions'"
            @sync="syncType('versions')"
          />
        </div>
      </div>

      <div v-if="activeTab === 'queues'" class="card">
        <table class="table">
          <thead>
            <tr>
              <th>Queue ID</th>
              <th>Map</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="queue in queues" :key="queue.queueId">
              <td>{{ queue.queueId }}</td>
              <td>{{ queue.map }}</td>
              <td>{{ queue.description || '-' }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="activeTab === 'maps'" class="card">
        <table class="table">
          <thead>
            <tr>
              <th>Map ID</th>
              <th>Map Name</th>
              <th>Notes</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="map in maps" :key="map.mapId">
              <td>{{ map.mapId }}</td>
              <td>{{ map.mapName }}</td>
              <td>{{ map.notes || '-' }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="activeTab === 'modes'" class="card">
        <table class="table">
          <thead>
            <tr>
              <th>Game Mode</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="mode in gameModes" :key="mode.gameMode">
              <td>{{ mode.gameMode }}</td>
              <td>{{ mode.description }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="activeTab === 'types'" class="card">
        <table class="table">
          <thead>
            <tr>
              <th>Game Type</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="type in gameTypes" :key="type.gameType">
              <td>{{ type.gameType }}</td>
              <td>{{ type.description }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="activeTab === 'versions'" class="card">
        <table class="table">
          <thead>
            <tr>
              <th>Version</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="version in versions" :key="version.version">
              <td>{{ version.version }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminPageTemplate>
</template>
