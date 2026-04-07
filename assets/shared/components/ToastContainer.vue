<script setup lang="ts">
import { useToastStore } from '@shared/stores/useToastStore'
import { CheckCircle2, XCircle, AlertTriangle, Info, X } from 'lucide-vue-next'

const store = useToastStore()

const iconMap = {
  success: CheckCircle2,
  error: XCircle,
  warning: AlertTriangle,
  info: Info,
}

const styleMap = {
  success: 'bg-admin-card border-admin-success/40 text-admin-success',
  error: 'bg-admin-card border-admin-danger/40 text-admin-danger',
  warning: 'bg-admin-card border-yellow-500/40 text-yellow-400',
  info: 'bg-admin-card border-admin-primary/40 text-admin-primary',
}
</script>

<template>
  <Teleport to="body">
    <div class="fixed top-4 right-4 z-9999 flex flex-col gap-2 pointer-events-none w-80">
      <TransitionGroup name="toast">
        <div
          v-for="toast in store.toasts"
          :key="toast.id"
          class="pointer-events-auto flex items-start gap-3 border rounded-xl px-4 py-3 shadow-2xl backdrop-blur-sm"
          :class="styleMap[toast.type]"
        >
          <component :is="iconMap[toast.type]" class="w-4 h-4 mt-0.5 shrink-0" />
          <p class="text-sm text-admin-heading flex-1 leading-snug">{{ toast.message }}</p>
          <button
            class="opacity-50 hover:opacity-100 transition-opacity shrink-0 mt-0.5"
            @click="store.remove(toast.id)"
          >
            <X class="w-3.5 h-3.5" />
          </button>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>

<style scoped>
.toast-enter-active,
.toast-leave-active {
  transition: all 0.25s ease;
}
.toast-enter-from,
.toast-leave-to {
  opacity: 0;
  transform: translateX(110%);
}
.toast-move {
  transition: transform 0.25s ease;
}
</style>
