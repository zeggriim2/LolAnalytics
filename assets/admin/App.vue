<script setup lang="ts">
import { RouterView, useRoute } from 'vue-router'
import AdminSidebar from '@admin/components/organisms/AdminSidebar.vue'
import ToastContainer from '@shared/components/ToastContainer.vue'

const route = useRoute()
</script>

<template>
  <div class="flex min-h-screen bg-admin-bg">
    <AdminSidebar v-if="route.name !== 'login'" />
    <main class="flex-1 flex flex-col min-w-0 overflow-hidden">
      <RouterView />
    </main>
  </div>
  <ToastContainer />
</template>
