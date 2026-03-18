<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { api } from '@shared/api/client'
import StatCard from '@admin/components/atoms/StatCard.vue'
import AdminPageTemplate from '@admin/components/templates/AdminPageTemplate.vue'

const matchesCount = ref(0)
const summonersCount = ref(0)
const loading = ref(true)
const error = ref<string | null>(null)

onMounted(async () => {
  try {
    const [matchesRes, summonersRes] = await Promise.all([api.getMatches(), api.getSummoners()])
    matchesCount.value = matchesRes.meta.total
    summonersCount.value = summonersRes.meta.total
  } catch (e) {
    error.value = 'Failed to load dashboard data'
    console.error(e)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <AdminPageTemplate>
    <template #header>
      <h2>Dashboard</h2>
    </template>

    <div v-if="loading" class="loading">Loading...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else class="stats-grid">
      <StatCard :value="matchesCount" label="Total Matches" />
      <StatCard :value="summonersCount" label="Total Summoners" />
    </div>
  </AdminPageTemplate>
</template>
