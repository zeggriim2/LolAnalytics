import { computed } from 'vue'
import { useRoute } from 'vue-router'

interface Crumb {
  label: string
  to?: string
}

const sectionMap: Record<string, Crumb[]> = {
  dashboard: [],
  matches: [{ label: 'Matches', to: '/matches' }],
  'match-detail': [{ label: 'Matches', to: '/matches' }, { label: 'Détail' }],
  summoners: [{ label: 'Summoners', to: '/summoners' }],
  'summoner-detail': [{ label: 'Summoners', to: '/summoners' }, { label: 'Profil' }],
  'game-data': [{ label: 'Game Data', to: '/game-data' }],
  'league-import': [{ label: 'League Import', to: '/league-import' }],
}

export function useBreadcrumbs() {
  const route = useRoute()

  const breadcrumbs = computed<Crumb[]>(() => {
    const name = route.name as string
    const crumbs: Crumb[] = [{ label: 'Dashboard', to: '/' }]

    if (!name || name === 'dashboard') return crumbs

    const extra = sectionMap[name] ?? []
    return [...crumbs, ...extra]
  })

  return { breadcrumbs }
}
