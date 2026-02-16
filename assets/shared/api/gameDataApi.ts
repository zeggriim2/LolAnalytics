import type { Queue, GameMap, GameMode, GameType, Version, ApiResponse } from '@shared/types'
import { fetchApi } from './fetchApi'

export const gameDataApi = {
  getQueues: () => fetchApi<ApiResponse<Queue[]>>('/game-data/queues'),
  getMaps: () => fetchApi<ApiResponse<GameMap[]>>('/game-data/maps'),
  getGameModes: () => fetchApi<ApiResponse<GameMode[]>>('/game-data/game-modes'),
  getGameTypes: () => fetchApi<ApiResponse<GameType[]>>('/game-data/game-types'),
  getVersions: () => fetchApi<ApiResponse<Version[]>>('/game-data/versions'),

  syncAll: () =>
    fetchApi<{ status: string; message: string }>('/admin/game-data/sync', { method: 'POST' }),
  sync: (type: string) =>
    fetchApi<{ status: string; message: string }>(`/admin/game-data/sync/${type}`, {
      method: 'POST',
    }),
}
