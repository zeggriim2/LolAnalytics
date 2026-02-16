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
  <div class="import-card card">
    <h3>Import Summoner</h3>
    <div class="mode-toggle">
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

    <form class="import-form" @submit.prevent="importSummoner">
      <template v-if="importMode === 'riot-id'">
        <input
          v-model="gameName"
          type="text"
          class="input"
          placeholder="Game Name"
          :disabled="importing"
        />
        <input
          v-model="tagLine"
          type="text"
          class="input"
          placeholder="Tag Line"
          :disabled="importing"
        />
      </template>
      <template v-else>
        <input
          v-model="puuid"
          type="text"
          class="input"
          placeholder="PUUID"
          :disabled="importing"
        />
      </template>

      <select v-model="selectedPlatform" class="input" :disabled="importing">
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

<style scoped>
.import-card {
  margin-bottom: 1rem;
}

.import-card h3 {
  margin-top: 0;
  margin-bottom: 0.75rem;
}

.mode-toggle {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 0.75rem;
}

.import-form {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  align-items: center;
}

.input {
  padding: 0.4rem 0.6rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 0.9rem;
}
</style>
