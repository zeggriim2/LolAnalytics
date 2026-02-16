import type { Summoner, ApiResponse } from '@shared/types'
import { fetchApi } from './fetchApi'

export const summonerApi = {
  getSummoners: (limit = 50, offset = 0) =>
    fetchApi<ApiResponse<Summoner[]>>(`/summoners?limit=${limit}&offset=${offset}`),
  getSummoner: (puuid: string) => fetchApi<ApiResponse<Summoner>>(`/summoners/${puuid}`),
}
