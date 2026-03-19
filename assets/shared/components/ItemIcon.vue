<script setup lang="ts">
const props = withDefaults(
  defineProps<{
    itemId: string
    version: string
    size?: number
  }>(),
  { size: 32 },
)

const imageUrl = `/images/items/${props.version}/${props.itemId}.png`
</script>

<template>
  <div class="item-icon" :style="{ width: `${size}px`, height: `${size}px` }">
    <img
      :src="imageUrl"
      :alt="`Item ${itemId}`"
      :title="`Item ${itemId}`"
      :width="size"
      :height="size"
      class="item-icon__img"
    />
  </div>
</template>

<style scoped>
.item-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.item-icon__img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 4px;
  border: 1px solid rgba(200, 155, 60, 0.4);
}
</style>
