import type { Match, ApiResponse, PaginatedResponse } from '@shared/types'
import { fetchApi } from './fetchApi'

export const matchApi = {
  getMatches: (page = 1, limit = 20) =>
    fetchApi<PaginatedResponse<Match>>(`/matches?page=${page}&limit=${limit}`),
  getMatch: (matchId: string) => fetchApi<ApiResponse<Match>>(`/matches/${matchId}`),
  getMatchesBySummoner: (puuid: string, page = 1, limit = 10) =>
    fetchApi<PaginatedResponse<Match>>(`/matches/summoner/${puuid}?page=${page}&limit=${limit}`),
}
