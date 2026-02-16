export interface Match {
  id: string
  gameId: number
  playedAt: string
  durationSeconds: number
  durationFormatted: string
  gameMode: string
  gameType: string
  mapId: number
  version: string
  queueId: number
  platform: string
  participantsCount: number
  participants?: Participant[]
}

export interface Participant {
  puuid: string
  summonerId: string
  championId: number
  kills: number
  deaths: number
  assists: number
  kda: string
  win: boolean
  items: string[]
}

export interface Summoner {
  puuid: string
  gameName: string
  tagLine: string
  riotId: string
  profileIconId: number
  summonerLevel: number
  platform: string
  lastUpdatedAt: string
}

export interface Queue {
  queueId: number
  map: string
  description: string | null
  notes: string | null
}

export interface GameMap {
  mapId: number
  mapName: string
  notes: string | null
}

export interface GameMode {
  gameMode: string
  description: string
}

export interface GameType {
  gameType: string
  description: string
}

export interface Version {
  version: string
}

export interface ApiResponse<T> {
  data: T
  total?: number
  limit?: number
  offset?: number
}
