<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useRoute, RouterLink } from 'vue-router'
import { api } from '@shared/api/client'
import type { Match } from '@shared/types'
import ChampionIcon from '@shared/components/ChampionIcon.vue'
import ItemIcon from '@shared/components/ItemIcon.vue'

const route = useRoute()
const match = ref<Match | null>(null)
const loading = ref(true)
const error = ref<string | null>(null)

const winners = computed(() => match.value?.participants?.filter((p) => p.win) || [])
const losers = computed(() => match.value?.participants?.filter((p) => !p.win) || [])

onMounted(async () => {
  try {
    const matchId = route.params.matchId as string
    const response = await api.getMatch(matchId)
    match.value = response.data
  } catch (e) {
    error.value = 'Failed to load match details'
    console.error(e)
  } finally {
    loading.value = false
  }
})

function formatDate(dateString: string): string {
  return new Date(dateString).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

function formatGold(value: number): string {
  return value.toLocaleString('fr-FR')
}
</script>

<template>
  <div>
    <header class="page-header">
      <RouterLink to="/matches" class="btn btn-secondary mr-4"> &larr; Back </RouterLink>
      <h2 class="inline">Match Details</h2>
    </header>

    <div v-if="loading" class="loading">Loading...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else-if="match">
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-value">{{ match.version }}</div>
          <div class="stat-label">Patch</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">{{ match.durationFormatted }}</div>
          <div class="stat-label">Duration</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">{{ match.gameMode }}</div>
          <div class="stat-label">Game Mode</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">{{ match.platform.toUpperCase() }}</div>
          <div class="stat-label">Platform</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">{{ formatDate(match.playedAt) }}</div>
          <div class="stat-label">Played At</div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h3>Winners</h3>
          <span class="badge bg-lol-win text-white">Victory</span>
        </div>
        <div class="table-wrapper">
          <table class="table">
            <thead>
              <tr>
                <th>Champion</th>
                <th>Summoner</th>
                <th>Lvl</th>
                <th>K/D/A</th>
                <th>KDA</th>
                <th>CS</th>
                <th>Gold</th>
                <th>Dmg dealt</th>
                <th>Dmg taken</th>
                <th>Vision</th>
                <th>Wards</th>
                <th>Items</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="participant in winners" :key="participant.puuid">
                <td><ChampionIcon :champion-id="participant.championId" :size="36" /></td>
                <td>
                  <div>{{ participant.gameName }}</div>
                  <RouterLink
                    :to="`/summoners/${participant.summonerId}`"
                    class="text-muted text-sm"
                  >
                    {{ participant.summonerId.substring(0, 8) }}...
                  </RouterLink>
                </td>
                <td>{{ participant.champLevel }}</td>
                <td>{{ participant.kills }}/{{ participant.deaths }}/{{ participant.assists }}</td>
                <td>{{ participant.kda }}</td>
                <td>{{ participant.cs }}</td>
                <td>{{ formatGold(participant.goldEarned) }}</td>
                <td>{{ formatGold(participant.totalDamageDealtToChampions) }}</td>
                <td>{{ formatGold(participant.totalDamageTaken) }}</td>
                <td>{{ participant.visionScore }}</td>
                <td>{{ participant.wardsPlaced }}/{{ participant.wardsKilled }}</td>
                <td>
                  <div class="items-row">
                    <ItemIcon
                      v-for="itemId in participant.items"
                      :key="itemId"
                      :item-id="itemId"
                      :version="match.version"
                      :size="28"
                    />
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h3>Losers</h3>
          <span class="badge bg-lol-loss text-white">Defeat</span>
        </div>
        <div class="table-wrapper">
          <table class="table">
            <thead>
              <tr>
                <th>Champion</th>
                <th>Summoner</th>
                <th>Lvl</th>
                <th>K/D/A</th>
                <th>KDA</th>
                <th>CS</th>
                <th>Gold</th>
                <th>Dmg dealt</th>
                <th>Dmg taken</th>
                <th>Vision</th>
                <th>Wards</th>
                <th>Items</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="participant in losers" :key="participant.puuid">
                <td><ChampionIcon :champion-id="participant.championId" :size="36" /></td>
                <td>
                  <div>{{ participant.gameName }}</div>
                  <RouterLink
                    :to="`/summoners/${participant.summonerId}`"
                    class="text-muted text-sm"
                  >
                    {{ participant.summonerId.substring(0, 8) }}...
                  </RouterLink>
                </td>
                <td>{{ participant.champLevel }}</td>
                <td>{{ participant.kills }}/{{ participant.deaths }}/{{ participant.assists }}</td>
                <td>{{ participant.kda }}</td>
                <td>{{ participant.cs }}</td>
                <td>{{ formatGold(participant.goldEarned) }}</td>
                <td>{{ formatGold(participant.totalDamageDealtToChampions) }}</td>
                <td>{{ formatGold(participant.totalDamageTaken) }}</td>
                <td>{{ participant.visionScore }}</td>
                <td>{{ participant.wardsPlaced }}/{{ participant.wardsKilled }}</td>
                <td>
                  <div class="items-row">
                    <ItemIcon
                      v-for="itemId in participant.items"
                      :key="itemId"
                      :item-id="itemId"
                      :version="match.version"
                      :size="28"
                    />
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.table-wrapper {
  overflow-x: auto;
}

.items-row {
  display: flex;
  gap: 2px;
  flex-wrap: wrap;
}

.text-muted {
  color: var(--lol-muted, #8a9bb2);
}

.text-sm {
  font-size: 0.75rem;
}
</style>
