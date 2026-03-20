<script setup lang="ts">
import { RouterLink, useRouter } from 'vue-router'
import { LayoutDashboard, Swords, Users, Database, LogOut } from 'lucide-vue-next'
import { useAuthStore } from '@admin/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

function logout() {
  authStore.logout()
  router.push('/login')
}
</script>

<template>
  <aside class="w-[250px] bg-lol-card p-4 border-r border-lol-border flex flex-col">
    <div class="p-4 border-b border-lol-border mb-4">
      <h1 class="text-xl text-lol-gold">LoL Analytics</h1>
      <small>Admin Panel</small>
    </div>
    <nav class="flex-1">
      <ul class="list-none">
        <li>
          <RouterLink
            to="/"
            class="flex items-center gap-3 py-3 px-4 text-lol-text no-underline rounded mb-1 transition-colors hover:bg-lol-bg hover:text-lol-gold"
            active-class="!bg-lol-bg !text-lol-gold"
          >
            <LayoutDashboard class="w-4 h-4 shrink-0" />
            Dashboard
          </RouterLink>
        </li>
        <li>
          <RouterLink
            to="/matches"
            class="flex items-center gap-3 py-3 px-4 text-lol-text no-underline rounded mb-1 transition-colors hover:bg-lol-bg hover:text-lol-gold"
            active-class="!bg-lol-bg !text-lol-gold"
          >
            <Swords class="w-4 h-4 shrink-0" />
            Matches
          </RouterLink>
        </li>
        <li>
          <RouterLink
            to="/summoners"
            class="flex items-center gap-3 py-3 px-4 text-lol-text no-underline rounded mb-1 transition-colors hover:bg-lol-bg hover:text-lol-gold"
            active-class="!bg-lol-bg !text-lol-gold"
          >
            <Users class="w-4 h-4 shrink-0" />
            Summoners
          </RouterLink>
        </li>
        <li>
          <RouterLink
            to="/game-data"
            class="flex items-center gap-3 py-3 px-4 text-lol-text no-underline rounded mb-1 transition-colors hover:bg-lol-bg hover:text-lol-gold"
            active-class="!bg-lol-bg !text-lol-gold"
          >
            <Database class="w-4 h-4 shrink-0" />
            Game Data
          </RouterLink>
        </li>
      </ul>
    </nav>
    <div class="border-t border-lol-border pt-4">
      <button
        class="w-full flex items-center gap-3 py-3 px-4 text-left text-lol-text rounded transition-colors hover:bg-lol-bg hover:text-red-400"
        @click="logout"
      >
        <LogOut class="w-4 h-4 shrink-0" />
        Logout
      </button>
    </div>
  </aside>
</template>
