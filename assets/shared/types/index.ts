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
  gameName: string | null
  cs: number
  goldEarned: number
  totalDamageDealtToChampions: number
  totalDamageTaken: number
  visionScore: number
  lane: string
  individualPosition: string
  summoner1Id: number
  summoner2Id: number
  champLevel: number
  wardsPlaced: number
  wardsKilled: number
  firstBloodKill: boolean
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

export interface Champion {
  riotId: string
  version: string
  championKey: string
  name: string
  title: string
  imageFull: string
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

export interface Platform {
  value: string
  label: string
}

export type LeagueTier = 'challenger' | 'grandmaster' | 'master'

export interface SummonerStats {
  totalGames: number
  wins: number
  losses: number
  winRate: number
  avgKills: number
  avgDeaths: number
  avgAssists: number
  avgKda: number
  avgCs: number
  avgCsPerMin: number
  avgGold: number
  favoriteChampionId: number | null
}

export interface PositionStat {
  position: string
  totalGames: number
  wins: number
  winRate: number
  avgKills: number
  avgDeaths: number
  avgAssists: number
  avgKda: number
  avgCs: number
}

export interface LeagueEntry {
  rank: number
  puuid: string
  gameName: string | null
  tagLine: string | null
  leaguePoints: number
  wins: number
  losses: number
  winRate: number
  hotStreak: boolean
  veteran: boolean
  freshBlood: boolean
}

export interface ApiResponse<T> {
  data: T
  total?: number
  limit?: number
  offset?: number
}

export interface PaginationMeta {
  total: number
  page: number
  limit: number
  totalPages: number
}

export interface PaginatedResponse<T> {
  data: T[]
  meta: PaginationMeta
}
