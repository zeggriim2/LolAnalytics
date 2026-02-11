<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { api } from '@shared/api/client'

const matchesCount = ref(0)
const summonersCount = ref(0)
const loading = ref(true)
const error = ref<string | null>(null)

onMounted(async () => {
  try {
    const [matchesRes, summonersRes] = await Promise.all([api.getMatches(), api.getSummoners()])
    matchesCount.value = matchesRes.total ?? 0
    summonersCount.value = summonersRes.total ?? 0
  } catch (e) {
    error.value = 'Failed to load dashboard data'
    console.error(e)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div>
    <header class="page-header">
      <h2>Dashboard</h2>
    </header>

    <div v-if="loading" class="loading">Loading...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else class="stats-grid">
      <div class="stat-card">
        <div class="stat-value">{{ matchesCount }}</div>
        <div class="stat-label">Total Matches</div>
      </div>
      <div class="stat-card">
        <div class="stat-value">{{ summonersCount }}</div>
        <div class="stat-label">Total Summoners</div>
      </div>
    </div>
  </div>
</template>
