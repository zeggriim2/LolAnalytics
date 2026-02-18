import type { Summoner, ApiResponse, PaginatedResponse } from '@shared/types'
import { fetchApi } from './fetchApi'

export const summonerApi = {
  getSummoners: (page = 1, limit = 20) =>
    fetchApi<PaginatedResponse<Summoner>>(`/summoners?page=${page}&limit=${limit}`),
  getSummoner: (puuid: string) => fetchApi<ApiResponse<Summoner>>(`/summoners/${puuid}`),
  importByRiotId: (gameName: string, tagLine: string, platform: string) =>
    fetchApi<{ status: string; message: string }>('/admin/summoners/import/riot-id', {
      method: 'POST',
      body: JSON.stringify({ gameName, tagLine, platform }),
    }),
  importByPuuid: (puuid: string, platform: string) =>
    fetchApi<{ status: string; message: string }>('/admin/summoners/import/puuid', {
      method: 'POST',
      body: JSON.stringify({ puuid, platform }),
    }),
}
