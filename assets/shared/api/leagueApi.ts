import type { LeagueEntry, LeagueTier, PaginatedResponse } from '@shared/types'
import { fetchApi } from './fetchApi'

export const leagueApi = {
  getEntries: (platform: string, tier: LeagueTier, queue: string, page = 1, limit = 50) =>
    fetchApi<PaginatedResponse<LeagueEntry>>(
      `/leagues?platform=${platform}&tier=${tier}&queue=${queue}&page=${page}&limit=${limit}`,
    ),

  refresh: (platform: string, tier: LeagueTier, queue: string) =>
    fetchApi<{ status: string; message: string }>('/leagues/refresh', {
      method: 'POST',
      body: JSON.stringify({ platform, tier, queue }),
    }),
}
