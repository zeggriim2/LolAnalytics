<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useRoute, RouterLink } from 'vue-router'
import { api } from '@shared/api/client'
import type { Match } from '@shared/types'
import ItemIcon from '@shared/components/ItemIcon.vue'
import ChampionIcon from '@shared/components/ChampionIcon.vue'
import { ChevronLeft, Clock, Swords, Globe, Calendar } from 'lucide-vue-next'

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
    error.value = 'Impossible de charger les détails de la partie'
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
  <div class="container py-8">
    <!-- Back -->
    <RouterLink
      to="/"
      class="inline-flex items-center gap-1.5 text-lol-muted text-sm no-underline hover:text-lol-text transition-colors mb-6"
    >
      <ChevronLeft class="w-4 h-4" />
      Retour à l'accueil
    </RouterLink>

    <div v-if="loading" class="loading">Chargement…</div>
    <div v-else-if="error" class="error">{{ error }}</div>

    <div v-else-if="match">
      <!-- Match stats bar -->
      <div class="flex flex-wrap gap-4 mb-8">
        <div
          class="flex items-center gap-2 bg-lol-card border border-lol-border rounded-xl px-4 py-3"
        >
          <Clock class="w-4 h-4 text-lol-gold" />
          <div>
            <p class="text-lol-text font-semibold text-sm">{{ match.durationFormatted }}</p>
            <p class="text-lol-muted text-xs">Durée</p>
          </div>
        </div>
        <div
          class="flex items-center gap-2 bg-lol-card border border-lol-border rounded-xl px-4 py-3"
        >
          <Swords class="w-4 h-4 text-lol-gold" />
          <div>
            <p class="text-lol-text font-semibold text-sm">{{ match.gameMode }}</p>
            <p class="text-lol-muted text-xs">Mode</p>
          </div>
        </div>
        <div
          class="flex items-center gap-2 bg-lol-card border border-lol-border rounded-xl px-4 py-3"
        >
          <Globe class="w-4 h-4 text-lol-gold" />
          <div>
            <p class="text-lol-text font-semibold text-sm">{{ match.platform.toUpperCase() }}</p>
            <p class="text-lol-muted text-xs">Serveur</p>
          </div>
        </div>
        <div
          class="flex items-center gap-2 bg-lol-card border border-lol-border rounded-xl px-4 py-3"
        >
          <Calendar class="w-4 h-4 text-lol-gold" />
          <div>
            <p class="text-lol-text font-semibold text-sm">{{ formatDate(match.playedAt) }}</p>
            <p class="text-lol-muted text-xs">Date</p>
          </div>
        </div>
      </div>

      <!-- Winners -->
      <div class="card">
        <div class="card-header">
          <h3 class="font-semibold text-lol-text">Équipe gagnante</h3>
          <span class="badge bg-lol-win text-white">Victoire</span>
        </div>
        <div class="overflow-x-auto">
          <table class="table">
            <thead>
              <tr>
                <th>Champion</th>
                <th>Invocateur</th>
                <th>Niv</th>
                <th>K/D/A</th>
                <th>KDA</th>
                <th>CS</th>
                <th>Or</th>
                <th>Dégâts</th>
                <th>Vision</th>
                <th>Wards</th>
                <th>Items</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="participant in winners" :key="participant.puuid">
                <td>
                  <ChampionIcon
                    :champion-id="participant.championId"
                    :version="match.version"
                    :size="36"
                  />
                </td>
                <td>
                  <RouterLink
                    :to="`/summoners/${participant.summonerId}`"
                    class="text-lol-gold no-underline hover:underline font-medium text-sm"
                  >
                    {{ participant.gameName }}
                  </RouterLink>
                </td>
                <td class="text-lol-muted text-sm">{{ participant.champLevel }}</td>
                <td class="font-mono text-sm">
                  {{ participant.kills }}/{{ participant.deaths }}/{{ participant.assists }}
                </td>
                <td class="text-sm">{{ participant.kda }}</td>
                <td class="text-lol-muted text-sm">{{ participant.cs }}</td>
                <td class="text-lol-muted text-sm">{{ formatGold(participant.goldEarned) }}</td>
                <td class="text-lol-muted text-sm">
                  {{ formatGold(participant.totalDamageDealtToChampions) }}
                </td>
                <td class="text-lol-muted text-sm">{{ participant.visionScore }}</td>
                <td class="text-lol-muted text-sm">
                  {{ participant.wardsPlaced }}/{{ participant.wardsKilled }}
                </td>
                <td>
                  <div class="flex gap-0.5 flex-wrap">
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

      <!-- Losers -->
      <div class="card">
        <div class="card-header">
          <h3 class="font-semibold text-lol-text">Équipe perdante</h3>
          <span class="badge bg-lol-loss text-white">Défaite</span>
        </div>
        <div class="overflow-x-auto">
          <table class="table">
            <thead>
              <tr>
                <th>Champion</th>
                <th>Invocateur</th>
                <th>Niv</th>
                <th>K/D/A</th>
                <th>KDA</th>
                <th>CS</th>
                <th>Or</th>
                <th>Dégâts</th>
                <th>Vision</th>
                <th>Wards</th>
                <th>Items</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="participant in losers" :key="participant.puuid">
                <td>
                  <ChampionIcon
                    :champion-id="participant.championId"
                    :version="match.version"
                    :size="36"
                  />
                </td>
                <td>
                  <RouterLink
                    :to="`/summoners/${participant.summonerId}`"
                    class="text-lol-gold no-underline hover:underline font-medium text-sm"
                  >
                    {{ participant.gameName }}
                  </RouterLink>
                </td>
                <td class="text-lol-muted text-sm">{{ participant.champLevel }}</td>
                <td class="font-mono text-sm">
                  {{ participant.kills }}/{{ participant.deaths }}/{{ participant.assists }}
                </td>
                <td class="text-sm">{{ participant.kda }}</td>
                <td class="text-lol-muted text-sm">{{ participant.cs }}</td>
                <td class="text-lol-muted text-sm">{{ formatGold(participant.goldEarned) }}</td>
                <td class="text-lol-muted text-sm">
                  {{ formatGold(participant.totalDamageDealtToChampions) }}
                </td>
                <td class="text-lol-muted text-sm">{{ participant.visionScore }}</td>
                <td class="text-lol-muted text-sm">
                  {{ participant.wardsPlaced }}/{{ participant.wardsKilled }}
                </td>
                <td>
                  <div class="flex gap-0.5 flex-wrap">
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
