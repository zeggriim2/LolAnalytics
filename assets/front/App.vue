<script setup lang="ts">
import { RouterView, RouterLink } from 'vue-router'
</script>

<template>
  <header class="main-header">
    <div class="container">
      <h1>
        <RouterLink to="/" style="color: inherit; text-decoration: none">LoL Analytics</RouterLink>
      </h1>
      <nav>
        <ul class="nav-links">
          <li><RouterLink to="/">Home</RouterLink></li>
          <li><RouterLink to="/summoners">Summoners</RouterLink></li>
        </ul>
      </nav>
    </div>
  </header>
  <main>
    <RouterView />
  </main>
</template>
