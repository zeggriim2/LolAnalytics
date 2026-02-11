import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../views/Dashboard.vue'
import MatchList from '../views/MatchList.vue'
import MatchDetail from '../views/MatchDetail.vue'
import SummonerList from '../views/SummonerList.vue'
import SummonerDetail from '../views/SummonerDetail.vue'
import GameData from '../views/GameData.vue'

const router = createRouter({
  history: createWebHistory('/admin'),
  routes: [
    {
      path: '/',
      name: 'dashboard',
      component: Dashboard,
    },
    {
      path: '/matches',
      name: 'matches',
      component: MatchList,
    },
    {
      path: '/matches/:matchId',
      name: 'match-detail',
      component: MatchDetail,
    },
    {
      path: '/summoners',
      name: 'summoners',
      component: SummonerList,
    },
    {
      path: '/summoners/:puuid',
      name: 'summoner-detail',
      component: SummonerDetail,
    },
    {
      path: '/game-data',
      name: 'game-data',
      component: GameData,
    },
  ],
})

export default router
