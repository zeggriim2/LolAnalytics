import { matchApi } from './matchApi'
import { summonerApi } from './summonerApi'
import { gameDataApi } from './gameDataApi'

export { matchApi } from './matchApi'
export { summonerApi } from './summonerApi'
export { gameDataApi } from './gameDataApi'

export const api = {
  ...matchApi,
  ...summonerApi,
  ...gameDataApi,
}
