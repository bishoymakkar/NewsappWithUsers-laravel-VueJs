import Vue from 'vue'
import Router from 'vue-router'
import BussinessNews from '@/components/BussinessNews'
import SportNews from '@/components/SportNews'
import Login from '@/components/Login'
import Register from '@/components/Register'
import Profile from '@/components/Profile'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'Home',
      redirect: '/BussinessNews'
    },
    {
      path: '/BussinessNews',
      name: 'BussinessNews',
      component: BussinessNews
    },
    {
      path: '/SportNews',
      name: 'SportNews',
      component: SportNews
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '/register',
      name: 'Register',
      component: Register
    },
    {
      path: '/profile',
      name: 'Profile',
      component: Profile
    }
  ]
})
