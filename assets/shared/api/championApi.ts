import type { ApiResponse, Champion } from '@shared/types'
import { fetchApi } from './fetchApi'

export const championApi = {
  getChampions: (version?: string) => {
    const query = version ? `?version=${version}` : ''
    return fetchApi<ApiResponse<Champion[]>>(`/champions${query}`)
  },
}
