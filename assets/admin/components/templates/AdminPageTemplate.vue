<script setup lang="ts">
import Breadcrumb from '@admin/components/molecules/Breadcrumb.vue'
import { useBreadcrumbs } from '@admin/composables/useBreadcrumbs'

const { breadcrumbs } = useBreadcrumbs()
</script>

<template>
  <div class="flex flex-col flex-1 min-h-screen">
    <!-- Top bar -->
    <header class="shrink-0 border-b border-admin-border bg-admin-sidebar">
      <!-- Breadcrumb row -->
      <div class="px-6 pt-3 pb-0">
        <Breadcrumb :crumbs="breadcrumbs" />
      </div>
      <!-- Title + actions row -->
      <div class="h-14 flex items-center justify-between px-6">
        <slot name="header" />
      </div>
    </header>

    <!-- Page content -->
    <div class="flex-1 p-6 overflow-y-auto">
      <slot />
    </div>
  </div>
</template>
