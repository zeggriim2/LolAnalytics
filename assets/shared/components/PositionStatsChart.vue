<script setup lang="ts">
import { computed } from 'vue'
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, CategoryScale, LinearScale, BarElement, Tooltip, Legend } from 'chart.js'
import type { PositionStat } from '@shared/types'

ChartJS.register(CategoryScale, LinearScale, BarElement, Tooltip, Legend)

const POSITION_LABELS: Record<string, string> = {
  TOP: 'Top',
  JUNGLE: 'Jungle',
  MIDDLE: 'Mid',
  BOTTOM: 'ADC',
  UTILITY: 'Support',
}

const props = defineProps<{
  positions: PositionStat[]
}>()

const labels = computed(() => props.positions.map((p) => POSITION_LABELS[p.position] ?? p.position))

const winRateData = computed(() => ({
  labels: labels.value,
  datasets: [
    {
      label: 'Win Rate (%)',
      data: props.positions.map((p) => p.winRate),
      backgroundColor: props.positions.map((p) =>
        p.winRate >= 50 ? 'rgba(30, 167, 253, 0.7)' : 'rgba(232, 64, 87, 0.7)',
      ),
      borderColor: props.positions.map((p) => (p.winRate >= 50 ? '#1ea7fd' : '#e84057')),
      borderWidth: 1,
      borderRadius: 4,
    },
  ],
}))

const kdaData = computed(() => ({
  labels: labels.value,
  datasets: [
    {
      label: 'Kills',
      data: props.positions.map((p) => p.avgKills),
      backgroundColor: 'rgba(30, 167, 253, 0.7)',
      borderRadius: 4,
    },
    {
      label: 'Deaths',
      data: props.positions.map((p) => p.avgDeaths),
      backgroundColor: 'rgba(232, 64, 87, 0.7)',
      borderRadius: 4,
    },
    {
      label: 'Assists',
      data: props.positions.map((p) => p.avgAssists),
      backgroundColor: 'rgba(200, 155, 60, 0.7)',
      borderRadius: 4,
    },
  ],
}))

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      labels: { color: '#a0aec0' },
    },
  },
  scales: {
    x: {
      ticks: { color: '#a0aec0' },
      grid: { color: 'rgba(255,255,255,0.05)' },
    },
    y: {
      ticks: { color: '#a0aec0' },
      grid: { color: 'rgba(255,255,255,0.05)' },
    },
  },
}
</script>

<template>
  <div class="card">
    <div class="card-header">
      <h3 class="font-semibold text-lol-text">Stats par rôle</h3>
      <span class="text-lol-muted text-sm">{{ positions.length }} rôles joués</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4">
      <!-- Win Rate par rôle -->
      <div>
        <p class="text-lol-muted text-xs mb-2 text-center">Win Rate par rôle</p>
        <div class="h-48">
          <Bar :data="winRateData" :options="chartOptions" />
        </div>
      </div>

      <!-- K/D/A par rôle -->
      <div>
        <p class="text-lol-muted text-xs mb-2 text-center">K/D/A moyen par rôle</p>
        <div class="h-48">
          <Bar :data="kdaData" :options="chartOptions" />
        </div>
      </div>
    </div>

    <!-- Tableau récapitulatif -->
    <div class="overflow-x-auto px-4 pb-4">
      <table class="table text-sm w-full">
        <thead>
          <tr>
            <th>Rôle</th>
            <th>Parties</th>
            <th>Win Rate</th>
            <th>KDA</th>
            <th>Moy. CS</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="pos in positions" :key="pos.position">
            <td class="font-medium text-lol-text">
              {{ POSITION_LABELS[pos.position] ?? pos.position }}
            </td>
            <td class="text-lol-muted">{{ pos.totalGames }}</td>
            <td>
              <span
                class="font-semibold"
                :class="pos.winRate >= 50 ? 'text-lol-win' : 'text-lol-loss'"
              >
                {{ pos.winRate }}%
              </span>
            </td>
            <td class="font-mono text-lol-text">
              {{ pos.avgKills }} / {{ pos.avgDeaths }} / {{ pos.avgAssists }}
              <span class="text-lol-muted text-xs ml-1">({{ pos.avgKda }})</span>
            </td>
            <td class="text-lol-muted">{{ pos.avgCs }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
