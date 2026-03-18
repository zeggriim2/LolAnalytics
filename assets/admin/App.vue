<script setup lang="ts">
import { RouterView, useRoute } from 'vue-router'
import AdminSidebar from '@admin/components/organisms/AdminSidebar.vue'

const route = useRoute()
</script>

<template>
  <div class="flex min-h-screen">
    <AdminSidebar v-if="route.name !== 'login'" />
    <main class="flex-1 p-8 overflow-y-auto">
      <RouterView />
    </main>
  </div>
</template>
