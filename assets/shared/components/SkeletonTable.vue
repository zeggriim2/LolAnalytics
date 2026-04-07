<script setup lang="ts">
const { rows = 6, cols = 5 } = defineProps<{
  rows?: number
  cols?: number
}>()

// Patterns de largeur déterministes pour éviter le recalcul à chaque render
const widthClasses = ['w-1/2', 'w-3/4', 'w-2/3', 'w-1/3', 'w-5/6', 'w-2/5', 'w-4/5']

function cellWidth(row: number, col: number): string {
  return widthClasses[(row * 7 + col * 3) % widthClasses.length]
}
</script>

<template>
  <table class="w-full border-collapse">
    <thead>
      <tr>
        <th
          v-for="c in cols"
          :key="c"
          class="py-3 px-4 border-b border-admin-border bg-admin-bg/40"
        >
          <div class="h-3 bg-admin-border/60 rounded animate-pulse w-16" />
        </th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="r in rows" :key="r">
        <td v-for="c in cols" :key="c" class="py-3.5 px-4 border-b border-admin-border/60">
          <div class="h-4 bg-admin-border/40 rounded animate-pulse" :class="cellWidth(r, c)" />
        </td>
      </tr>
    </tbody>
  </table>
</template>
