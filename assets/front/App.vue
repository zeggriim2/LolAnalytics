<script setup lang="ts">
import { RouterView, RouterLink } from 'vue-router'
</script>

<template>
  <div class="min-h-screen flex flex-col">
    <!-- Header sticky avec backdrop blur -->
    <header class="sticky top-0 z-50 bg-lol-card/80 backdrop-blur-md border-b border-lol-border/60">
      <div class="container flex items-center justify-between h-14">
        <RouterLink to="/" class="no-underline flex items-center gap-2 group">
          <span
            class="text-lol-gold font-bold text-lg tracking-wide group-hover:opacity-90 transition-opacity"
          >
            LoL<span class="text-lol-text font-light">Analytics</span>
          </span>
        </RouterLink>

        <nav>
          <ul class="flex gap-1 list-none">
            <li>
              <RouterLink
                to="/"
                class="px-3 py-1.5 text-sm text-lol-muted no-underline rounded-lg transition-colors hover:text-lol-text hover:bg-lol-border/40"
                active-class="!text-lol-gold !bg-lol-gold/10"
              >
                Accueil
              </RouterLink>
            </li>
            <li>
              <RouterLink
                to="/summoners"
                class="px-3 py-1.5 text-sm text-lol-muted no-underline rounded-lg transition-colors hover:text-lol-text hover:bg-lol-border/40"
                active-class="!text-lol-gold !bg-lol-gold/10"
              >
                Invocateurs
              </RouterLink>
            </li>
          </ul>
        </nav>
      </div>
    </header>

    <main class="flex-1">
      <RouterView />
    </main>

    <footer class="border-t border-lol-border/40 py-5 text-center text-lol-muted/60 text-xs">
      LoL Analytics — Powered by Riot Games API
    </footer>
  </div>
</template>
