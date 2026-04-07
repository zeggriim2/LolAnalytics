import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useSidebarStore = defineStore('sidebar', () => {
  const collapsed = ref(localStorage.getItem('sidebar_collapsed') === 'true')

  function toggle() {
    collapsed.value = !collapsed.value
    localStorage.setItem('sidebar_collapsed', String(collapsed.value))
  }

  return { collapsed, toggle }
})
