<script setup lang="ts">
import { ref, computed, watch, onUnmounted } from 'vue'

export interface SelectOption {
  value: string
  label: string
}

const props = defineProps<{
  modelValue: string | undefined
  options: SelectOption[]
  placeholder?: string
}>()

const emit = defineEmits<{
  'update:modelValue': [value: string]
}>()

const isOpen = ref(false)
const search = ref('')
const containerRef = ref<HTMLElement | null>(null)

const selectedLabel = computed(() =>
  props.modelValue ? (props.options.find((o) => o.value === props.modelValue)?.label ?? '') : '',
)

const filteredOptions = computed(() => {
  const q = search.value.toLowerCase()
  return q ? props.options.filter((o) => o.label.toLowerCase().includes(q)) : props.options
})

function open() {
  isOpen.value = true
  search.value = ''
}

function select(value: string) {
  emit('update:modelValue', value)
  isOpen.value = false
  search.value = ''
}

function clear() {
  emit('update:modelValue', '')
  isOpen.value = false
  search.value = ''
}

function onClickOutside(event: MouseEvent) {
  if (containerRef.value && !containerRef.value.contains(event.target as Node)) {
    isOpen.value = false
    search.value = ''
  }
}

watch(isOpen, (val) => {
  if (val) {
    document.addEventListener('mousedown', onClickOutside)
  } else {
    document.removeEventListener('mousedown', onClickOutside)
  }
})

onUnmounted(() => {
  document.removeEventListener('mousedown', onClickOutside)
})
</script>

<template>
  <div ref="containerRef" class="searchable-select">
    <!-- Trigger -->
    <div
      class="searchable-select__trigger"
      :class="{ 'searchable-select__trigger--active': isOpen }"
      @click="open"
    >
      <template v-if="isOpen">
        <input
          v-model="search"
          class="searchable-select__input"
          :placeholder="selectedLabel || placeholder"
          autofocus
          @click.stop
        />
      </template>
      <template v-else>
        <span :class="modelValue ? 'text-lol-text' : 'text-lol-muted'">
          {{ selectedLabel || placeholder }}
        </span>
        <button
          v-if="modelValue"
          class="searchable-select__clear"
          type="button"
          @click.stop="clear"
        >
          ✕
        </button>
        <span v-else class="searchable-select__chevron">▾</span>
      </template>
    </div>

    <!-- Dropdown -->
    <ul v-if="isOpen" class="searchable-select__dropdown">
      <li
        v-if="!filteredOptions.length"
        class="searchable-select__option searchable-select__option--empty"
      >
        Aucun résultat
      </li>
      <li
        v-for="option in filteredOptions"
        :key="option.value"
        class="searchable-select__option"
        :class="{ 'searchable-select__option--selected': option.value === modelValue }"
        @mousedown.prevent="select(option.value)"
      >
        {{ option.label }}
      </li>
    </ul>
  </div>
</template>
