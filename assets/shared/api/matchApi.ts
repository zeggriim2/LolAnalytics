import type { Match, ApiResponse } from '@shared/types'
import { fetchApi } from './fetchApi'

export const matchApi = {
  getMatches: () => fetchApi<ApiResponse<Match[]>>('/matches'),
  getMatch: (matchId: string) => fetchApi<ApiResponse<Match>>(`/matches/${matchId}`),
}
