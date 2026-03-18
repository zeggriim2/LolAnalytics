<script setup lang="ts">
import { RouterLink } from 'vue-router'
import type { Participant } from '@shared/types'
import ChampionIcon from '@shared/components/ChampionIcon.vue'
import ItemIcon from '@shared/components/ItemIcon.vue'
import WinBadge from '@admin/components/atoms/WinBadge.vue'

defineProps<{
  participants: Participant[]
  version: string
  title: string
  win: boolean
}>()

function formatGold(value: number): string {
  return value.toLocaleString('fr-FR')
}
</script>

<template>
  <div class="card">
    <div class="card-header">
      <h3>{{ title }}</h3>
      <WinBadge :win="win" />
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
          <tr v-for="participant in participants" :key="participant.puuid">
            <td><ChampionIcon :champion-id="participant.championId" :size="36" /></td>
            <td>
              <div>{{ participant.gameName }}</div>
              <RouterLink :to="`/summoners/${participant.summonerId}`" class="text-muted text-sm">
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
                  :version="version"
                  :size="28"
                />
              </div>
            </td>
          </tr>
        </tbody>
      </table>
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
