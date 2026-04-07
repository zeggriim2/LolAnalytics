<script setup lang="ts">
import { RouterLink, useRouter, useRoute } from 'vue-router'
import {
  LayoutDashboard,
  Swords,
  Users,
  Database,
  Trophy,
  LogOut,
  PanelLeftClose,
  PanelLeftOpen,
} from 'lucide-vue-next'
import { useAuthStore } from '@admin/stores/auth'
import { useSidebarStore } from '@admin/stores/useSidebarStore'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()
const sidebar = useSidebarStore()

function logout() {
  authStore.logout()
  router.push('/login')
}

const navItems = [
  { to: '/', icon: LayoutDashboard, label: 'Dashboard', exact: true },
  { to: '/matches', icon: Swords, label: 'Matches' },
  { to: '/summoners', icon: Users, label: 'Summoners' },
  { to: '/game-data', icon: Database, label: 'Game Data' },
  { to: '/league-import', icon: Trophy, label: 'League Import' },
]

function isActive(to: string, exact = false) {
  if (exact) return route.path === to
  return route.path === to || route.path.startsWith(to + '/')
}
</script>

<template>
  <aside
    class="min-h-screen bg-admin-sidebar border-r border-admin-border flex flex-col shrink-0 transition-[width] duration-300 ease-in-out overflow-hidden"
    :class="sidebar.collapsed ? 'w-16' : 'w-64'"
  >
    <!-- Logo -->
    <div
      class="h-16 flex items-center border-b border-admin-border shrink-0 px-4"
      :class="sidebar.collapsed ? 'justify-center' : 'gap-3'"
    >
      <div
        class="w-8 h-8 rounded-lg bg-admin-primary flex items-center justify-center shrink-0 shadow-lg shadow-admin-primary/30"
      >
        <Swords class="w-4 h-4 text-white" />
      </div>
      <Transition name="fade">
        <div v-if="!sidebar.collapsed" class="leading-tight min-w-0">
          <p class="text-admin-heading font-semibold text-sm truncate">LoL Analytics</p>
          <p class="text-admin-text text-xs">Admin Panel</p>
        </div>
      </Transition>
    </div>

    <!-- Navigation -->
    <nav
      class="flex-1 py-5 space-y-0.5 overflow-hidden"
      :class="sidebar.collapsed ? 'px-2' : 'px-3'"
    >
      <Transition name="fade">
        <p
          v-if="!sidebar.collapsed"
          class="px-3 mb-2 text-xs font-semibold text-admin-text/50 uppercase tracking-widest"
        >
          Menu
        </p>
      </Transition>

      <RouterLink
        v-for="item in navItems"
        :key="item.to"
        :to="item.to"
        class="flex items-center rounded-lg text-sm no-underline transition-all duration-150 group"
        :class="[
          sidebar.collapsed ? 'justify-center w-10 h-10 mx-auto' : 'gap-3 px-3 py-2.5',
          isActive(item.to, item.exact)
            ? 'bg-admin-primary/10 text-admin-primary font-medium'
            : 'text-admin-text hover:bg-admin-card hover:text-admin-heading',
        ]"
        :title="sidebar.collapsed ? item.label : undefined"
      >
        <component
          :is="item.icon"
          class="w-4 h-4 shrink-0 transition-colors"
          :class="
            isActive(item.to, item.exact)
              ? 'text-admin-primary'
              : 'text-admin-text/70 group-hover:text-admin-heading'
          "
        />
        <Transition name="fade">
          <span v-if="!sidebar.collapsed" class="truncate">{{ item.label }}</span>
        </Transition>
      </RouterLink>
    </nav>

    <!-- Footer -->
    <div class="border-t border-admin-border shrink-0" :class="sidebar.collapsed ? 'p-2' : 'p-3'">
      <!-- Toggle collapse -->
      <button
        class="flex items-center rounded-lg text-admin-text transition-all duration-150 hover:bg-admin-card hover:text-admin-heading mb-1 group"
        :class="sidebar.collapsed ? 'justify-center w-10 h-10 mx-auto' : 'gap-3 px-3 py-2.5 w-full'"
        :title="sidebar.collapsed ? 'Développer' : undefined"
        @click="sidebar.toggle()"
      >
        <component
          :is="sidebar.collapsed ? PanelLeftOpen : PanelLeftClose"
          class="w-4 h-4 shrink-0 text-admin-text/70 group-hover:text-admin-heading transition-colors"
        />
        <Transition name="fade">
          <span v-if="!sidebar.collapsed" class="text-sm truncate">Réduire</span>
        </Transition>
      </button>

      <!-- Logout -->
      <button
        class="flex items-center rounded-lg text-admin-text transition-all duration-150 hover:bg-red-500/10 hover:text-red-400 group"
        :class="sidebar.collapsed ? 'justify-center w-10 h-10 mx-auto' : 'gap-3 px-3 py-2.5 w-full'"
        :title="sidebar.collapsed ? 'Se déconnecter' : undefined"
        @click="logout"
      >
        <LogOut
          class="w-4 h-4 shrink-0 text-admin-text/70 group-hover:text-red-400 transition-colors"
        />
        <Transition name="fade">
          <span v-if="!sidebar.collapsed" class="text-sm truncate">Se déconnecter</span>
        </Transition>
      </button>
    </div>
  </aside>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.15s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
