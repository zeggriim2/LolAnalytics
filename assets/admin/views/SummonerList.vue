<script setup lang="ts">
import { onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { Users } from 'lucide-vue-next'
import { summonerApi } from '@shared/api/summonerApi'
import type { Summoner } from '@shared/types'
import { usePagination } from '@shared/composables/usePagination'
import { useToast } from '@shared/composables/useToast'
import PaginationBar from '@shared/components/PaginationBar.vue'
import SkeletonTable from '@shared/components/SkeletonTable.vue'
import SummonerImportForm from '@admin/components/organisms/SummonerImportForm.vue'
import AdminPageTemplate from '@admin/components/templates/AdminPageTemplate.vue'

const {
  items: summoners,
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
} = usePagination<Summoner>((page, limit) => summonerApi.getSummoners(page, limit))

const toast = useToast()

async function onImportSuccess(message: string) {
  await fetchPage(meta.value.page)
  toast.success(message)
}

function onImportError(message: string) {
  toast.error(message)
}

function formatDate(dateString: string): string {
  return new Date(dateString).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  })
}

onMounted(() => fetchPage(1))
</script>

<template>
  <AdminPageTemplate>
    <template #header>
      <div class="flex items-center gap-2">
        <Users class="w-5 h-5 text-admin-primary" />
        <h2 class="text-admin-heading font-semibold text-lg">Summoners</h2>
      </div>
    </template>

    <SummonerImportForm @success="onImportSuccess" @error="onImportError" />

    <div v-if="error" class="error">{{ error }}</div>
    <div class="card">
      <SkeletonTable v-if="initialLoading" :rows="8" :cols="5" />
      <div
        v-else
        class="transition-opacity duration-200"
        :class="{ 'opacity-50 pointer-events-none': loading }"
      >
        <table class="table">
          <thead>
            <tr>
              <th>Riot ID</th>
              <th>Level</th>
              <th>Platform</th>
              <th>Last Updated</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="summoner in summoners" :key="summoner.puuid">
              <td>{{ summoner.riotId }}</td>
              <td>{{ summoner.summonerLevel }}</td>
              <td>{{ summoner.platform.toUpperCase() }}</td>
              <td>{{ formatDate(summoner.lastUpdatedAt) }}</td>
              <td>
                <RouterLink :to="`/summoners/${summoner.puuid}`" class="btn btn-primary">
                  View
                </RouterLink>
              </td>
            </tr>
            <tr v-if="summoners.length === 0">
              <td colspan="5" class="text-center">No summoners found</td>
            </tr>
          </tbody>
        </table>
      </div>

      <PaginationBar
        v-if="!initialLoading"
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
