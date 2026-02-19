<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { gameDataApi } from '@shared/api/gameDataApi'
import type { Queue, GameMap, GameMode, GameType, Version } from '@shared/types'
import AlertMessage from '@shared/components/AlertMessage.vue'
import SpinnerButton from '@shared/components/SpinnerButton.vue'

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
  <div>
    <header class="page-header flex items-center justify-between">
      <h2>Game Data</h2>
      <SpinnerButton
        :loading="syncingAll"
        :disabled="isSyncing()"
        label="Sync All"
        loading-label="Syncing..."
        @click="syncAll"
      />
    </header>

    <AlertMessage v-if="syncMessage" :type="syncMessage.type" :message="syncMessage.text" />

    <div v-if="loading" class="loading">Loading...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else>
      <div class="mb-4">
        <div class="flex flex-wrap gap-1">
          <div class="inline-flex items-center gap-1">
            <button
              class="btn ml-2"
              :class="activeTab === 'queues' ? 'btn-primary' : 'btn-secondary'"
              @click="activeTab = 'queues'"
            >
              Queues ({{ queues.length }})
            </button>
            <button
              class="btn"
              :disabled="isSyncing('queues')"
              @click="syncType('queues')"
            >
              <span v-if="syncingType === 'queues'" class="spinner"></span>
              {{ syncingType === 'queues' ? '...' : 'Sync' }}
            </button>
          </div>
          <div class="inline-flex items-center gap-1">
            <button
              class="btn ml-2"
              :class="activeTab === 'maps' ? 'btn-primary' : 'btn-secondary'"
              @click="activeTab = 'maps'"
            >
              Maps ({{ maps.length }})
            </button>
            <button
              class="btn text-xs px-2 py-0.5"
              :disabled="isSyncing('maps')"
              @click="syncType('maps')"
            >
              <span v-if="syncingType === 'maps'" class="spinner"></span>
              {{ syncingType === 'maps' ? '...' : 'Sync' }}
            </button>
          </div>
          <div class="inline-flex items-center gap-1">
            <button
              class="btn ml-2"
              :class="activeTab === 'modes' ? 'btn-primary' : 'btn-secondary'"
              @click="activeTab = 'modes'"
            >
              Game Modes ({{ gameModes.length }})
            </button>
            <button
              class="btn text-xs px-2 py-0.5"
              :disabled="isSyncing('game-modes')"
              @click="syncType('game-modes')"
            >
              <span v-if="syncingType === 'game-modes'" class="spinner"></span>
              {{ syncingType === 'game-modes' ? '...' : 'Sync' }}
            </button>
          </div>
          <div class="inline-flex items-center gap-1">
            <button
              class="btn ml-2"
              :class="activeTab === 'types' ? 'btn-primary' : 'btn-secondary'"
              @click="activeTab = 'types'"
            >
              Game Types ({{ gameTypes.length }})
            </button>
            <button
              class="btn text-xs px-2 py-0.5"
              :disabled="isSyncing('game-types')"
              @click="syncType('game-types')"
            >
              <span v-if="syncingType === 'game-types'" class="spinner"></span>
              {{ syncingType === 'game-types' ? '...' : 'Sync' }}
            </button>
          </div>
          <div class="inline-flex items-center gap-1">
            <button
              class="btn ml-2"
              :class="activeTab === 'versions' ? 'btn-primary' : 'btn-secondary'"
              @click="activeTab = 'versions'"
            >
              Version ({{ versions.length }})
            </button>
            <button
              class="btn text-xs px-2 py-0.5"
              :disabled="isSyncing('versions')"
              @click="syncType('versions')"
            >
              <span v-if="syncingType === 'versions'" class="spinner"></span>
              {{ syncingType === 'versions' ? '...' : 'Sync' }}
            </button>
          </div>
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
  </div>
</template>
