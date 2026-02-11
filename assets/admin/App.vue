<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
  <div class="admin-layout">
    <aside class="sidebar">
      <div class="sidebar-header">
        <h1>LoL Analytics</h1>
        <small>Admin Panel</small>
      </div>
      <nav>
        <ul class="sidebar-nav">
          <li>
            <RouterLink to="/">Dashboard</RouterLink>
          </li>
          <li>
            <RouterLink to="/matches">Matches</RouterLink>
          </li>
          <li>
            <RouterLink to="/summoners">Summoners</RouterLink>
          </li>
          <li>
            <RouterLink to="/game-data">Game Data</RouterLink>
          </li>
        </ul>
      </nav>
    </aside>
    <main class="main-content">
      <RouterView />
    </main>
  </div>
</template>
