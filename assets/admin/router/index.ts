import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../views/Dashboard.vue'
import Login from '../views/Login.vue'
import MatchList from '../views/MatchList.vue'
import MatchDetail from '../views/MatchDetail.vue'
import SummonerList from '../views/SummonerList.vue'
import SummonerDetail from '../views/SummonerDetail.vue'
import GameData from '../views/GameData.vue'
import LeagueImport from '../views/LeagueImport.vue'
import LeagueLeaderboard from '../views/LeagueLeaderboard.vue'

const router = createRouter({
  history: createWebHistory('/admin'),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: Login,
      meta: { requiresAuth: false },
    },
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
    {
      path: '/league-import',
      name: 'league-import',
      component: LeagueImport,
    },
    {
      path: '/league-leaderboard',
      name: 'league-leaderboard',
      component: LeagueLeaderboard,
    },
  ],
})

router.beforeEach((to) => {
  const token = localStorage.getItem('admin_token')
  const requiresAuth = to.meta.requiresAuth !== false

  if (requiresAuth && !token) {
    return { name: 'login' }
  }

  if (!requiresAuth && token && to.name === 'login') {
    return { name: 'dashboard' }
  }
})

export default router
