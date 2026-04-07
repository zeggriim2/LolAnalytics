import { useToastStore } from '@shared/stores/useToastStore'

export function useToast() {
  const store = useToastStore()
  return {
    success: (message: string, duration?: number) => store.add('success', message, duration),
    error: (message: string, duration?: number) => store.add('error', message, duration),
    warning: (message: string, duration?: number) => store.add('warning', message, duration),
    info: (message: string, duration?: number) => store.add('info', message, duration),
  }
}
