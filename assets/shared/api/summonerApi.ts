import type { Summoner, ApiResponse } from '@shared/types'
import { fetchApi } from './fetchApi'

export const summonerApi = {
  getSummoners: (limit = 50, offset = 0) =>
    fetchApi<ApiResponse<Summoner[]>>(`/summoners?limit=${limit}&offset=${offset}`),
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
