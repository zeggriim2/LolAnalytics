<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { authApi } from '@shared/api/authApi'
import { useAuthStore } from '../stores/auth'
import { Swords, Mail, Lock, AlertCircle } from 'lucide-vue-next'

const router = useRouter()
const authStore = useAuthStore()

const email = ref('')
const password = ref('')
const error = ref<string | null>(null)
const loading = ref(false)

async function handleLogin() {
  error.value = null
  loading.value = true
  try {
    const { token } = await authApi.login(email.value, password.value)
    authStore.setToken(token)
    await router.push('/')
  } catch {
    error.value = 'Identifiants invalides. Veuillez réessayer.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen bg-admin-bg flex items-center justify-center p-4">
    <!-- Background glow -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
      <div
        class="absolute -top-40 left-1/2 -translate-x-1/2 w-96 h-96 bg-admin-primary/10 rounded-full blur-3xl"
      />
    </div>

    <div class="relative w-full max-w-md">
      <!-- Logo / branding -->
      <div class="flex flex-col items-center mb-8">
        <div
          class="w-14 h-14 rounded-2xl bg-admin-primary flex items-center justify-center mb-4 shadow-xl shadow-admin-primary/30"
        >
          <Swords class="w-7 h-7 text-white" />
        </div>
        <h1 class="text-admin-heading text-2xl font-bold tracking-tight">LoL Analytics</h1>
        <p class="text-admin-text text-sm mt-1">Admin — Connectez-vous pour continuer</p>
      </div>

      <!-- Card -->
      <div class="bg-admin-card border border-admin-border rounded-2xl p-8 shadow-2xl">
        <form class="space-y-5" @submit.prevent="handleLogin">
          <!-- Email -->
          <div>
            <label class="block text-admin-heading text-sm font-medium mb-1.5" for="email">
              Email
            </label>
            <div class="relative">
              <Mail
                class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-admin-text pointer-events-none"
              />
              <input
                id="email"
                v-model="email"
                type="email"
                required
                autocomplete="email"
                placeholder="admin@example.com"
                class="admin-input pl-10"
              />
            </div>
          </div>

          <!-- Password -->
          <div>
            <label class="block text-admin-heading text-sm font-medium mb-1.5" for="password">
              Mot de passe
            </label>
            <div class="relative">
              <Lock
                class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-admin-text pointer-events-none"
              />
              <input
                id="password"
                v-model="password"
                type="password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
                class="admin-input pl-10"
              />
            </div>
          </div>

          <!-- Error -->
          <div
            v-if="error"
            class="flex items-center gap-2 bg-red-500/10 border border-red-500/30 rounded-lg px-4 py-3 text-red-400 text-sm"
          >
            <AlertCircle class="w-4 h-4 shrink-0" />
            {{ error }}
          </div>

          <!-- Submit -->
          <button
            type="submit"
            :disabled="loading"
            class="admin-btn-primary w-full py-2.5 text-sm font-semibold"
          >
            {{ loading ? 'Connexion…' : 'Se connecter' }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>
