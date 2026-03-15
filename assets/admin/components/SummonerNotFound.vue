<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { platformApi } from '@shared/api/platformApi'
import { summonerApi } from '@shared/api/summonerApi'
import type { Platform } from '@shared/types'

const props = defineProps<{ puuid: string }>()
const emit = defineEmits<{ synced: [] }>()

const platforms = ref<Platform[]>([])
const selectedPlatform = ref('')
const loading = ref(false)
const error = ref<string | null>(null)

onMounted(async () => {
  const res = await platformApi.getPlatforms()
  platforms.value = res.data
  if (platforms.value.length > 0) {
    selectedPlatform.value = platforms.value[0].value
  }
})

async function sync() {
  loading.value = true
  error.value = null
  try {
    await summonerApi.importByPuuid(props.puuid, selectedPlatform.value)
    emit('synced')
  } catch (e) {
    error.value = 'Sync failed. Check the puuid and platform.'
    console.error(e)
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="card">
    <h3 class="mb-4">Summoner not found</h3>
    <p class="text-lol-muted mb-4">
      This summoner is not yet in the database. Select a platform and sync to import it from Riot
      API.
    </p>
    <p class="text-lol-muted mb-6">
      PUUID: <code>{{ puuid }}</code>
    </p>
    <div class="flex items-center gap-4">
      <select v-model="selectedPlatform" class="input">
        <option v-for="p in platforms" :key="p.value" :value="p.value">{{ p.label }}</option>
      </select>
      <button class="btn btn-primary" :disabled="loading" @click="sync">
        <span v-if="loading">Syncing...</span>
        <span v-else>Sync from Riot API</span>
      </button>
    </div>
    <div v-if="error" class="error mt-4">{{ error }}</div>
  </div>
</template>
