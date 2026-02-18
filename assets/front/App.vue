<script setup lang="ts">
import { RouterView, RouterLink } from 'vue-router'
</script>

<template>
  <header class="bg-lol-card py-4 border-b-2 border-lol-gold">
    <div class="container flex items-center justify-between">
      <h1 class="text-lol-gold text-2xl">
        <RouterLink to="/" class="text-inherit no-underline">LoL Analytics</RouterLink>
      </h1>
      <nav>
        <ul class="flex gap-6 list-none">
          <li>
            <RouterLink
              to="/"
              class="text-lol-text no-underline text-sm transition-colors hover:text-lol-gold"
              active-class="!text-lol-gold"
            >
              Home
            </RouterLink>
          </li>
          <li>
            <RouterLink
              to="/summoners"
              class="text-lol-text no-underline text-sm transition-colors hover:text-lol-gold"
              active-class="!text-lol-gold"
            >
              Summoners
            </RouterLink>
          </li>
        </ul>
      </nav>
    </div>
  </header>
  <main>
    <RouterView />
  </main>
</template>
