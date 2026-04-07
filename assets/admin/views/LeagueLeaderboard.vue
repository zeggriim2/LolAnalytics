<script setup lang="ts">
import { ref, watch, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { Flame, Shield, Star } from 'lucide-vue-next'
import { leagueApi } from '@shared/api/leagueApi'
import type { LeagueEntry, LeagueTier } from '@shared/types'
import AdminPageTemplate from '@admin/components/templates/AdminPageTemplate.vue'
import AlertMessage from '@shared/components/AlertMessage.vue'
import SpinnerButton from '@shared/components/SpinnerButton.vue'
import PaginationBar from '@shared/components/PaginationBar.vue'

const TIERS: LeagueTier[] = ['challenger', 'grandmaster', 'master']
const PLATFORMS = ['euw1', 'na1', 'kr', 'eun1', 'br1', 'la1', 'la2', 'oc1', 'tr1', 'ru', 'jp1']
const QUEUES = [
  { value: 'RANKED_SOLO_5x5', label: 'Ranked Solo/Duo' },
  { value: 'RANKED_FLEX_SR', label: 'Ranked Flex' },
]

const platform = ref('euw1')
const tier = ref<LeagueTier>('challenger')
const queue = ref('RANKED_SOLO_5x5')

const entries = ref<LeagueEntry[]>([])
const loading = ref(false)
const refreshing = ref(false)
const error = ref<string | null>(null)
const message = ref<{ type: 'success' | 'error'; text: string } | null>(null)

const page = ref(1)
const limit = 50
const total = ref(0)
const totalPages = ref(0)

const meta = ref({ total: 0, page: 1, limit, totalPages: 0 })

function showMessage(type: 'success' | 'error', text: string) {
  message.value = { type, text }
  setTimeout(() => (message.value = null), 5000)
}

async function fetchEntries(p = 1) {
  loading.value = true
  error.value = null
  try {
    const res = await leagueApi.getEntries(platform.value, tier.value, queue.value, p, limit)
    entries.value = res.data
    meta.value = res.meta
    page.value = res.meta.page
    total.value = res.meta.total
    totalPages.value = res.meta.totalPages
  } catch {
    error.value = 'No data found. Run a refresh first.'
    entries.value = []
  } finally {
    loading.value = false
  }
}

async function refresh() {
  refreshing.value = true
  message.value = null
  try {
    const res = await leagueApi.refresh(platform.value, tier.value, queue.value)
    showMessage('success', res.message)
    await fetchEntries(1)
  } catch (e) {
    showMessage('error', e instanceof Error ? e.message : 'Refresh failed')
  } finally {
    refreshing.value = false
  }
}

function tierLabel(t: string) {
  return t.charAt(0).toUpperCase() + t.slice(1)
}

function tierColor(t: LeagueTier): string {
  return {
    challenger: 'text-yellow-400',
    grandmaster: 'text-red-400',
    master: 'text-purple-400',
  }[t]
}

watch([platform, tier, queue], () => fetchEntries(1))
onMounted(() => fetchEntries(1))
</script>

<template>
  <AdminPageTemplate>
    <template #header>
      <div class="flex items-center justify-between w-full">
        <h2>League Leaderboard</h2>
        <SpinnerButton
          :loading="refreshing"
          :disabled="refreshing || loading"
          label="Refresh from Riot API"
          loading-label="Refreshing..."
          @click="refresh"
        />
      </div>
    </template>

    <AlertMessage v-if="message" :type="message.type" :message="message.text" class="mb-4" />

    <div class="bg-lol-card rounded-lg border border-lol-border p-4 mb-6">
      <div class="flex flex-wrap gap-4 items-end">
        <div>
          <label class="block text-xs text-lol-muted mb-1">Platform</label>
          <select
            v-model="platform"
            class="bg-lol-bg border border-lol-border rounded px-3 py-2 text-lol-text text-sm"
          >
            <option v-for="p in PLATFORMS" :key="p" :value="p">{{ p.toUpperCase() }}</option>
          </select>
        </div>
        <div>
          <label class="block text-xs text-lol-muted mb-1">Tier</label>
          <div class="flex gap-2">
            <button
              v-for="t in TIERS"
              :key="t"
              class="px-4 py-2 rounded text-sm font-medium border transition-colors"
              :class="
                tier === t
                  ? 'border-lol-gold bg-lol-bg ' + tierColor(t)
                  : 'border-lol-border text-lol-muted hover:border-lol-gold hover:text-lol-text'
              "
              @click="tier = t"
            >
              {{ tierLabel(t) }}
            </button>
          </div>
        </div>
        <div>
          <label class="block text-xs text-lol-muted mb-1">Queue</label>
          <select
            v-model="queue"
            class="bg-lol-bg border border-lol-border rounded px-3 py-2 text-lol-text text-sm"
          >
            <option v-for="q in QUEUES" :key="q.value" :value="q.value">{{ q.label }}</option>
          </select>
        </div>
      </div>
    </div>

    <div v-if="loading" class="text-lol-muted py-8 text-center">Loading...</div>
    <div v-else-if="error" class="text-lol-muted py-8 text-center">{{ error }}</div>
    <div v-else class="card">
      <div class="flex items-center justify-between mb-3 px-1">
        <span :class="['text-lg font-semibold', tierColor(tier)]">
          {{ tierLabel(tier) }} — {{ platform.toUpperCase() }}
        </span>
        <span class="text-sm text-lol-muted">{{ total }} players</span>
      </div>

      <table class="table">
        <thead>
          <tr>
            <th class="w-12">#</th>
            <th>Summoner</th>
            <th class="text-right">LP</th>
            <th class="text-right">W</th>
            <th class="text-right">L</th>
            <th class="text-right">Win Rate</th>
            <th class="text-center">Badges</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="entry in entries" :key="entry.puuid">
            <td class="text-lol-muted text-sm">{{ entry.rank }}</td>
            <td class="font-mono text-xs text-lol-muted">{{ entry.puuid.slice(0, 16) }}…</td>
            <td class="text-right font-semibold" :class="tierColor(tier)">
              {{ entry.leaguePoints }} LP
            </td>
            <td class="text-right text-green-400">{{ entry.wins }}</td>
            <td class="text-right text-red-400">{{ entry.losses }}</td>
            <td class="text-right">
              <span
                class="text-sm font-medium"
                :class="
                  entry.winRate >= 55
                    ? 'text-green-400'
                    : entry.winRate >= 50
                      ? 'text-lol-text'
                      : 'text-red-400'
                "
              >
                {{ entry.winRate }}%
              </span>
            </td>
            <td class="text-center">
              <div class="flex justify-center gap-1">
                <span v-if="entry.hotStreak" title="Hot Streak"
                  ><Flame class="w-4 h-4 text-orange-400"
                /></span>
                <span v-if="entry.veteran" title="Veteran"
                  ><Shield class="w-4 h-4 text-blue-400"
                /></span>
                <span v-if="entry.freshBlood" title="Fresh Blood"
                  ><Star class="w-4 h-4 text-green-400"
                /></span>
              </div>
            </td>
            <td>
              <RouterLink
                v-if="entry.puuid"
                :to="`/summoners/${entry.puuid}`"
                class="btn btn-primary text-xs"
              >
                View
              </RouterLink>
            </td>
          </tr>
          <tr v-if="entries.length === 0">
            <td colspan="8" class="text-center text-lol-muted py-6">
              No data — run a refresh to populate the leaderboard.
            </td>
          </tr>
        </tbody>
      </table>

      <PaginationBar
        :meta="meta"
        :has-previous="page > 1"
        :has-next="page < totalPages"
        @previous="fetchEntries(page - 1)"
        @next="fetchEntries(page + 1)"
        @go-to-page="fetchEntries"
      />
    </div>
  </AdminPageTemplate>
</template>
