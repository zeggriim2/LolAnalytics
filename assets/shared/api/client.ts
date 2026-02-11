import type {
  Match,
  Summoner,
  Queue,
  GameMap,
  GameMode,
  GameType,
  Version,
  ApiResponse,
} from '@shared/types'

const API_BASE = '/api'

async function fetchApi<T>(endpoint: string, options?: RequestInit): Promise<T> {
  const response = await fetch(`${API_BASE}${endpoint}`, {
    headers: {
      'Content-Type': 'application/json',
      ...options?.headers,
    },
    ...options,
  })

  if (!response.ok) {
    throw new Error(`API Error: ${response.status} ${response.statusText}`)
  }

  return response.json()
}

export const api = {
  // Matches
  getMatches: () => fetchApi<ApiResponse<Match[]>>('/matches'),
  getMatch: (matchId: string) => fetchApi<ApiResponse<Match>>(`/matches/${matchId}`),

  // Summoners
  getSummoners: (limit = 50, offset = 0) =>
    fetchApi<ApiResponse<Summoner[]>>(`/summoners?limit=${limit}&offset=${offset}`),
  getSummoner: (puuid: string) => fetchApi<ApiResponse<Summoner>>(`/summoners/${puuid}`),

  // Game Data
  getQueues: () => fetchApi<ApiResponse<Queue[]>>('/game-data/queues'),
  getMaps: () => fetchApi<ApiResponse<GameMap[]>>('/game-data/maps'),
  getGameModes: () => fetchApi<ApiResponse<GameMode[]>>('/game-data/game-modes'),
  getGameTypes: () => fetchApi<ApiResponse<GameType[]>>('/game-data/game-types'),
  getVersions: () => fetchApi<ApiResponse<Version[]>>('/game-data/versions'),
}
