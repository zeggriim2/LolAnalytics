<script setup lang="ts">
defineProps<{
  win: boolean
}>()
</script>

<template>
  <span class="badge text-white" :class="win ? 'bg-lol-win' : 'bg-lol-loss'">
    {{ win ? 'Victory' : 'Defeat' }}
  </span>
</template>
