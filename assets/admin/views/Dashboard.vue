<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { api } from '@shared/api/client'
import StatCard from '@admin/components/atoms/StatCard.vue'
import AdminPageTemplate from '@admin/components/templates/AdminPageTemplate.vue'
import { Swords, Users, Activity } from 'lucide-vue-next'

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
    error.value = 'Impossible de charger les données du tableau de bord'
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
        <Activity class="w-5 h-5 text-admin-primary" />
        <h2 class="text-admin-heading font-semibold text-lg">Dashboard</h2>
      </div>
    </template>

    <div v-if="loading" class="flex items-center justify-center h-48 text-admin-text">
      <span class="animate-pulse">Chargement…</span>
    </div>

    <div
      v-else-if="error"
      class="bg-red-500/10 border border-red-500/30 text-red-400 rounded-xl p-4 text-sm"
    >
      {{ error }}
    </div>

    <div v-else>
      <p class="text-admin-text text-sm mb-5">Vue d'ensemble de vos données LoL Analytics.</p>
      <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <StatCard :value="matchesCount" label="Total Matches" :icon="Swords" />
        <StatCard :value="summonersCount" label="Total Summoners" :icon="Users" />
      </div>
    </div>
  </AdminPageTemplate>
</template>
