<script setup lang="ts">
import { computed, onMounted } from 'vue'
import { useChampions } from '@shared/composables/useChampions'

const props = withDefaults(
  defineProps<{
    championId: number
    version: string
    size?: number
  }>(),
  { size: 40 },
)

const { load, findById, getImageUrl } = useChampions()

onMounted(() => load(props.version))

const champion = computed(() => findById(props.championId, props.version))
const imageUrl = computed(() => (champion.value ? getImageUrl(champion.value) : null))
</script>

<template>
  <div class="champion-icon" :style="{ width: `${size}px`, height: `${size}px` }">
    <img
      v-if="imageUrl"
      :src="imageUrl"
      :alt="champion?.name ?? `Champion ${championId}`"
      :title="champion?.name"
      :width="size"
      :height="size"
      class="champion-icon__img"
    />
    <span v-else class="champion-icon__fallback" :title="`Champion ${championId}`">
      {{ championId }}
    </span>
  </div>
</template>

<style scoped>
.champion-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.champion-icon__img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 4px;
  border: 1px solid rgba(200, 155, 60, 0.4);
}

.champion-icon__fallback {
  font-size: 0.65rem;
  color: var(--lol-muted, #8a9bb2);
}
</style>
