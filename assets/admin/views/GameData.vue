<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { api } from '@shared/api/client'
import type { Queue, GameMap, GameMode, GameType, Version } from '@shared/types'

const queues = ref<Queue[]>([])
const maps = ref<GameMap[]>([])
const gameModes = ref<GameMode[]>([])
const gameTypes = ref<GameType[]>([])
const versions = ref<Version[]>([])
const loading = ref(true)
const error = ref<string | null>(null)
const activeTab = ref('queues')

onMounted(async () => {
  try {
    const [queuesRes, mapsRes, modesRes, typesRes, versionsRes] = await Promise.all([
      api.getQueues(),
      api.getMaps(),
      api.getGameModes(),
      api.getGameTypes(),
      api.getVersions(),
    ])
    queues.value = queuesRes.data
    maps.value = mapsRes.data
    gameModes.value = modesRes.data
    gameTypes.value = typesRes.data
    versions.value = versionsRes.data
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
    <header class="page-header">
      <h2>Game Data</h2>
    </header>

    <div v-if="loading" class="loading">Loading...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else>
      <div class="panel">
        <button
          class="btn"
          :class="activeTab === 'queues' ? 'btn-primary' : 'btn-secondary'"
          @click="activeTab = 'queues'"
        >
          Queues ({{ queues.length }})
        </button>
        <button
          class="btn"
          :class="activeTab === 'maps' ? 'btn-primary' : 'btn-secondary'"
          @click="activeTab = 'maps'"
        >
          Maps ({{ maps.length }})
        </button>
        <button
          class="btn"
          :class="activeTab === 'modes' ? 'btn-primary' : 'btn-secondary'"
          @click="activeTab = 'modes'"
        >
          Game Modes ({{ gameModes.length }})
        </button>
        <button
          class="btn"
          :class="activeTab === 'types' ? 'btn-primary' : 'btn-secondary'"
          @click="activeTab = 'types'"
        >
          Game Types ({{ gameTypes.length }})
        </button>
        <button
          class="btn"
          :class="activeTab === 'versions' ? 'btn-primary' : 'btn-secondary'"
          @click="activeTab = 'versions'"
        >
          Version ({{ versions.length }})
        </button>
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

<style scoped>
.btn {
  margin-left: 0.5rem;
}

.panel {
  margin-bottom: 1rem;
}

</style>
