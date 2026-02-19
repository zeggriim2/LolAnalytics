<script setup lang="ts">
import { computed } from 'vue'
import { Doughnut, Line } from 'vue-chartjs'
import {
  Chart as ChartJS,
  ArcElement,
  Tooltip,
  Legend,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
} from 'chart.js'
import type { Match, Participant } from '@shared/types'

ChartJS.register(ArcElement, Tooltip, Legend, CategoryScale, LinearScale, PointElement, LineElement)

const props = defineProps<{
  matches: Match[]
  summonerPuuid: string
}>()

function findParticipant(match: Match): Participant | undefined {
  return match.participants?.find((p) => p.puuid === props.summonerPuuid)
}

const playerStats = computed(() => {
  return props.matches
    .map((match) => {
      const p = findParticipant(match)
      if (!p) return null
      return { match, participant: p }
    })
    .filter((s): s is NonNullable<typeof s> => s !== null)
})

const wins = computed(() => playerStats.value.filter((s) => s.participant.win).length)
const losses = computed(() => playerStats.value.filter((s) => !s.participant.win).length)

const winLossData = computed(() => ({
  labels: ['Victories', 'Defeats'],
  datasets: [
    {
      data: [wins.value, losses.value],
      backgroundColor: ['#1ea7fd', '#e84057'],
    },
  ],
}))

const kdaLineData = computed(() => ({
  labels: playerStats.value.map((_, i) => `#${i + 1}`),
  datasets: [
    {
      label: 'KDA',
      data: playerStats.value.map((s) => {
        const p = s.participant
        return p.deaths === 0 ? p.kills + p.assists : (p.kills + p.assists) / p.deaths
      }),
      borderColor: '#c89b3c',
      backgroundColor: 'rgba(200, 155, 60, 0.1)',
      tension: 0.3,
      fill: true,
    },
  ],
}))

const championData = computed(() => {
  const counts: Record<number, number> = {}
  for (const s of playerStats.value) {
    const id = s.participant.championId
    counts[id] = (counts[id] || 0) + 1
  }

  const labels = Object.keys(counts).map((id) => `Champion #${id}`)
  const data = Object.values(counts)
  const colors = [
    '#1ea7fd',
    '#e84057',
    '#c89b3c',
    '#0acbe6',
    '#9b59b6',
    '#2ecc71',
    '#e67e22',
    '#1abc9c',
    '#f39c12',
    '#3498db',
  ]

  return {
    labels,
    datasets: [
      {
        data,
        backgroundColor: colors.slice(0, data.length),
      },
    ],
  }
})

const doughnutOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      labels: { color: '#a09b8c' },
    },
  },
}

const lineOptions = {
  responsive: true,
  maintainAspectRatio: false,
  scales: {
    x: {
      ticks: { color: '#a09b8c' },
      grid: { color: 'rgba(160,155,140,0.1)' },
    },
    y: {
      ticks: { color: '#a09b8c' },
      grid: { color: 'rgba(160,155,140,0.1)' },
      beginAtZero: true,
    },
  },
  plugins: {
    legend: {
      labels: { color: '#a09b8c' },
    },
  },
}
</script>

<template>
  <div v-if="playerStats.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="card">
      <div class="card-header">
        <h3>Win / Loss</h3>
      </div>
      <div class="h-52">
        <Doughnut :data="winLossData" :options="doughnutOptions" />
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h3>KDA Curve</h3>
      </div>
      <div class="h-52">
        <Line :data="kdaLineData" :options="lineOptions" />
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h3>Champions Played</h3>
      </div>
      <div class="h-52">
        <Doughnut :data="championData" :options="doughnutOptions" />
      </div>
    </div>
  </div>
</template>
