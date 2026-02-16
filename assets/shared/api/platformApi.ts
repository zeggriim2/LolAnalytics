import type { ApiResponse, Platform } from '@shared/types'
import { fetchApi } from './fetchApi'

export const platformApi = {
  getPlatforms: () => fetchApi<ApiResponse<Platform[]>>('/platforms'),
}
