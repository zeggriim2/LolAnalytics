import type { Match, ApiResponse, PaginatedResponse } from '@shared/types'
import { fetchApi } from './fetchApi'

export interface MatchFilters {
  platform?: string
  gameMode?: string
  version?: string
  dateFrom?: string
  dateTo?: string
}

function buildMatchesUrl(page: number, limit: number, filters: MatchFilters = {}): string {
  const params = new URLSearchParams({ page: String(page), limit: String(limit) })
  if (filters.platform) params.set('platform', filters.platform)
  if (filters.gameMode) params.set('gameMode', filters.gameMode)
  if (filters.version) params.set('version', filters.version)
  if (filters.dateFrom) params.set('dateFrom', filters.dateFrom)
  if (filters.dateTo) params.set('dateTo', filters.dateTo)
  return `/matches?${params.toString()}`
}

export const matchApi = {
  getMatches: (page = 1, limit = 20, filters: MatchFilters = {}) =>
    fetchApi<PaginatedResponse<Match>>(buildMatchesUrl(page, limit, filters)),
  getMatch: (matchId: string) => fetchApi<ApiResponse<Match>>(`/matches/${matchId}`),
  getMatchesBySummoner: (puuid: string, page = 1, limit = 10) =>
    fetchApi<PaginatedResponse<Match>>(`/matches/summoner/${puuid}?page=${page}&limit=${limit}`),
  syncMatchesBySummoner: (puuid: string) =>
    fetchApi<void>(`/matches/summoner/${puuid}/sync`, { method: 'POST' }),
}
