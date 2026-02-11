import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'

const router = createRouter({
  history: createWebHistory('/'),
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home,
    },
    {
      path: '/summoners',
      name: 'summoner-search',
      component: () => import('../views/SummonerSearch.vue'),
    },
    {
      path: '/summoners/:puuid',
      name: 'summoner-detail',
      component: () => import('../views/SummonerDetail.vue'),
    },
    {
      path: '/matches/:matchId',
      name: 'match-detail',
      component: () => import('../views/MatchDetail.vue'),
    },
  ],
})

export default router
