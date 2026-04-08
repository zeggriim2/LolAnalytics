<script setup lang="ts">
import ChampionIcon from '@shared/components/ChampionIcon.vue'
import type { SummonerStats } from '@shared/types'

defineProps<{
  stats: SummonerStats
  version: string
}>()

const winRateColor = (rate: number) =>
  rate >= 55 ? 'text-lol-win' : rate >= 50 ? 'text-lol-text' : 'text-lol-loss'

const kdaColor = (kda: number) =>
  kda >= 3 ? 'text-lol-win' : kda >= 2 ? 'text-lol-gold' : 'text-lol-text'
</script>

<template>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <!-- Win Rate -->
    <div class="card flex flex-col gap-2">
      <p class="text-xs text-lol-muted uppercase tracking-widest font-medium">Win Rate</p>
      <p class="text-3xl font-bold" :class="winRateColor(stats.winRate)">{{ stats.winRate }}%</p>
      <div class="flex items-center gap-2 text-sm mt-auto">
        <span class="text-lol-win font-medium">{{ stats.wins }}V</span>
        <span class="text-lol-muted">·</span>
        <span class="text-lol-loss font-medium">{{ stats.losses }}D</span>
        <span class="text-lol-muted text-xs ml-auto">{{ stats.totalGames }} parties</span>
      </div>
    </div>

    <!-- KDA -->
    <div class="card flex flex-col gap-2">
      <p class="text-xs text-lol-muted uppercase tracking-widest font-medium">KDA Moyen</p>
      <p class="text-3xl font-bold" :class="kdaColor(stats.avgKda)">
        {{ stats.avgKda }}
      </p>
      <p class="text-sm text-lol-muted mt-auto">
        <span class="text-lol-text">{{ stats.avgKills }}</span>
        /
        <span class="text-lol-loss">{{ stats.avgDeaths }}</span>
        /
        <span class="text-lol-text">{{ stats.avgAssists }}</span>
      </p>
    </div>

    <!-- CS/min -->
    <div class="card flex flex-col gap-2">
      <p class="text-xs text-lol-muted uppercase tracking-widest font-medium">CS / min</p>
      <p class="text-3xl font-bold text-lol-text">{{ stats.avgCsPerMin }}</p>
      <p class="text-sm text-lol-muted mt-auto">{{ stats.avgCs }} CS en moyenne</p>
    </div>

    <!-- Favori champion -->
    <div class="card flex flex-col gap-2">
      <p class="text-xs text-lol-muted uppercase tracking-widest font-medium">Champion Favori</p>
      <div class="flex items-center gap-3 mt-1">
        <ChampionIcon
          v-if="stats.favoriteChampionId"
          :champion-id="stats.favoriteChampionId"
          :version="version"
          :size="48"
          class="rounded-lg"
        />
        <span v-else class="text-lol-muted text-sm">—</span>
      </div>
      <p class="text-xs text-lol-muted mt-auto">Avg {{ stats.avgGold.toLocaleString() }} or</p>
    </div>
  </div>
</template>
