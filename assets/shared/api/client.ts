import { matchApi } from './matchApi'
import { summonerApi } from './summonerApi'
import { gameDataApi } from './gameDataApi'
import { platformApi } from './platformApi'

export { matchApi } from './matchApi'
export { summonerApi } from './summonerApi'
export { gameDataApi } from './gameDataApi'
export { platformApi } from './platformApi'

export const api = {
  ...matchApi,
  ...summonerApi,
  ...gameDataApi,
  ...platformApi,
}
