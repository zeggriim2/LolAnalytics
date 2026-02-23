<script setup lang="ts">
import { RouterLink, RouterView, useRouter, useRoute } from 'vue-router'
import { useAuthStore } from './stores/auth'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

function logout() {
  authStore.logout()
  router.push('/login')
}
</script>

<template>
  <div class="flex min-h-screen">
    <aside
      v-if="route.name !== 'login'"
      class="w-[250px] bg-lol-card p-4 border-r border-lol-border flex flex-col"
    >
      <div class="p-4 border-b border-lol-border mb-4">
        <h1 class="text-xl text-lol-gold">LoL Analytics</h1>
        <small>Admin Panel</small>
      </div>
      <nav class="flex-1">
        <ul class="list-none">
          <li>
            <RouterLink
              to="/"
              class="block py-3 px-4 text-lol-text no-underline rounded mb-1 transition-colors hover:bg-lol-bg hover:text-lol-gold"
              active-class="!bg-lol-bg !text-lol-gold"
            >
              Dashboard
            </RouterLink>
          </li>
          <li>
            <RouterLink
              to="/matches"
              class="block py-3 px-4 text-lol-text no-underline rounded mb-1 transition-colors hover:bg-lol-bg hover:text-lol-gold"
              active-class="!bg-lol-bg !text-lol-gold"
            >
              Matches
            </RouterLink>
          </li>
          <li>
            <RouterLink
              to="/summoners"
              class="block py-3 px-4 text-lol-text no-underline rounded mb-1 transition-colors hover:bg-lol-bg hover:text-lol-gold"
              active-class="!bg-lol-bg !text-lol-gold"
            >
              Summoners
            </RouterLink>
          </li>
          <li>
            <RouterLink
              to="/game-data"
              class="block py-3 px-4 text-lol-text no-underline rounded mb-1 transition-colors hover:bg-lol-bg hover:text-lol-gold"
              active-class="!bg-lol-bg !text-lol-gold"
            >
              Game Data
            </RouterLink>
          </li>
        </ul>
      </nav>
      <div class="border-t border-lol-border pt-4">
        <button
          class="w-full py-3 px-4 text-left text-lol-text rounded transition-colors hover:bg-lol-bg hover:text-red-400"
          @click="logout"
        >
          Logout
        </button>
      </div>
    </aside>
    <main class="flex-1 p-8 overflow-y-auto">
      <RouterView />
    </main>
  </div>
</template>
