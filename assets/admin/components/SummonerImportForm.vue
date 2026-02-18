<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { platformApi } from '@shared/api/platformApi.ts'
import { summonerApi } from '@shared/api/summonerApi.ts'
import type { Platform } from '@shared/types'
import SpinnerButton from '@shared/components/SpinnerButton.vue'

const emit = defineEmits<{
  success: [message: string]
  error: [message: string]
}>()

const platforms = ref<Platform[]>([])
const importMode = ref<'riot-id' | 'puuid'>('riot-id')
const gameName = ref('')
const tagLine = ref('')
const puuid = ref('')
const selectedPlatform = ref('')
const importing = ref(false)

async function importSummoner() {
  if (importMode.value === 'riot-id') {
    if (!gameName.value || !tagLine.value || !selectedPlatform.value) {
      emit('error', 'All fields are required.')
      return
    }
  } else {
    if (!puuid.value || !selectedPlatform.value) {
      emit('error', 'All fields are required.')
      return
    }
  }

  importing.value = true
  try {
    const res =
      importMode.value === 'riot-id'
        ? await summonerApi.importByRiotId(gameName.value, tagLine.value, selectedPlatform.value)
        : await summonerApi.importByPuuid(puuid.value, selectedPlatform.value)
    gameName.value = ''
    tagLine.value = ''
    puuid.value = ''
    emit('success', res.message)
  } catch (e) {
    emit('error', e instanceof Error ? e.message : 'Import failed')
  } finally {
    importing.value = false
  }
}

onMounted(async () => {
  const res = await platformApi.getPlatforms()
  platforms.value = res.data
  if (platforms.value.length > 0) {
    selectedPlatform.value = platforms.value[0].value
  }
})
</script>

<template>
  <div class="card mb-4">
    <h3 class="mt-0 mb-3">Import Summoner</h3>
    <div class="flex gap-2 mb-3">
      <button
        class="btn"
        :class="importMode === 'riot-id' ? 'btn-primary' : 'btn-secondary'"
        @click="importMode = 'riot-id'"
      >
        By Riot ID
      </button>
      <button
        class="btn"
        :class="importMode === 'puuid' ? 'btn-primary' : 'btn-secondary'"
        @click="importMode = 'puuid'"
      >
        By PUUID
      </button>
    </div>

    <form class="flex flex-wrap gap-2 items-center" @submit.prevent="importSummoner">
      <template v-if="importMode === 'riot-id'">
        <input
          v-model="gameName"
          type="text"
          class="px-2.5 py-1.5 border border-lol-border rounded text-sm bg-lol-card text-lol-text"
          placeholder="Game Name"
          :disabled="importing"
        />
        <input
          v-model="tagLine"
          type="text"
          class="px-2.5 py-1.5 border border-lol-border rounded text-sm bg-lol-card text-lol-text"
          placeholder="Tag Line"
          :disabled="importing"
        />
      </template>
      <template v-else>
        <input
          v-model="puuid"
          type="text"
          class="px-2.5 py-1.5 border border-lol-border rounded text-sm bg-lol-card text-lol-text"
          placeholder="PUUID"
          :disabled="importing"
        />
      </template>

      <select
        v-model="selectedPlatform"
        class="px-2.5 py-1.5 border border-lol-border rounded text-sm bg-lol-card text-lol-text"
        :disabled="importing"
      >
        <option v-for="p in platforms" :key="p.value" :value="p.value">
          {{ p.label }}
        </option>
      </select>

      <SpinnerButton
        type="submit"
        :loading="importing"
        label="Import"
        loading-label="Importing..."
      />
    </form>
  </div>
</template>
