<script setup lang="ts">
import { ref } from 'vue'
import { Trophy } from 'lucide-vue-next'
import { summonerApi } from '@shared/api/summonerApi'
import { useToast } from '@shared/composables/useToast'
import AdminPageTemplate from '@admin/components/templates/AdminPageTemplate.vue'
import SpinnerButton from '@shared/components/SpinnerButton.vue'

const TIERS = ['challenger', 'grandmaster', 'master'] as const
const PLATFORMS = ['euw1', 'na1', 'kr', 'eun1', 'br1', 'la1', 'la2', 'oc1', 'tr1', 'ru', 'jp1']
const QUEUES = [
  { value: 'RANKED_SOLO_5x5', label: 'Ranked Solo/Duo' },
  { value: 'RANKED_FLEX_SR', label: 'Ranked Flex' },
]

const platform = ref('euw1')
const queue = ref('RANKED_SOLO_5x5')
const loadingTier = ref<string | null>(null)
const toast = useToast()

async function importTier(tier: string) {
  loadingTier.value = tier
  try {
    const res = await summonerApi.importTopLeague(platform.value, tier, queue.value)
    toast.success(res.message)
  } catch (e) {
    toast.error(e instanceof Error ? e.message : 'Import failed')
  } finally {
    loadingTier.value = null
  }
}

function tierLabel(tier: string): string {
  return tier.charAt(0).toUpperCase() + tier.slice(1)
}
</script>

<template>
  <AdminPageTemplate>
    <template #header>
      <div class="flex items-center gap-2">
        <Trophy class="w-5 h-5 text-admin-primary" />
        <h2 class="text-admin-heading font-semibold text-lg">League Import</h2>
      </div>
    </template>

    <div class="bg-lol-card rounded-lg border border-lol-border p-6 mb-6">
      <h3 class="text-lol-gold mb-4">Import Settings</h3>
      <div class="grid grid-cols-2 gap-4 max-w-lg">
        <div>
          <label class="block text-sm text-lol-muted mb-1">Platform</label>
          <select
            v-model="platform"
            class="w-full bg-lol-bg border border-lol-border rounded px-3 py-2 text-lol-text"
          >
            <option v-for="p in PLATFORMS" :key="p" :value="p">{{ p }}</option>
          </select>
        </div>
        <div>
          <label class="block text-sm text-lol-muted mb-1">Queue</label>
          <select
            v-model="queue"
            class="w-full bg-lol-bg border border-lol-border rounded px-3 py-2 text-lol-text"
          >
            <option v-for="q in QUEUES" :key="q.value" :value="q.value">{{ q.label }}</option>
          </select>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-3 gap-4">
      <div
        v-for="tier in TIERS"
        :key="tier"
        class="bg-lol-card rounded-lg border border-lol-border p-6 flex flex-col items-center gap-4"
      >
        <h3 class="text-lol-gold text-lg">{{ tierLabel(tier) }}</h3>
        <p class="text-sm text-lol-muted text-center">
          Enqueue all {{ tierLabel(tier) }} summoners on {{ platform }} for async import.
        </p>
        <SpinnerButton
          :loading="loadingTier === tier"
          :disabled="loadingTier !== null"
          :label="`Import ${tierLabel(tier)}`"
          :loading-label="`Importing...`"
          class="w-full"
          @click="importTier(tier)"
        />
      </div>
    </div>
  </AdminPageTemplate>
</template>
