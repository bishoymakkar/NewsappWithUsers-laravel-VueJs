<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample10" aria-controls="navbarsExample10"
      aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-md-center" id="navbarsExample10">
      <ul class="navbar-nav">
        <li class="nav-item">
          <router-link class="nav-link" to="/BussinessNews">Business News</router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" to="/SportNews">Sports News</router-link>
        </li>
        <li v-if="auth==''" class="nav-item">
          <router-link class="nav-link" to="/login">Login</router-link>
        </li>
        <li v-if="auth==''" class="nav-item">
          <router-link class="nav-link" to="/register">Register</router-link>
        </li>
        <li v-if="auth=='loggedin'" class="nav-item">
          <router-link class="nav-link" to="/profile">Profile</router-link>
        </li>
        <li v-if="auth=='loggedin'" class="nav-item">
          <a class="nav-link" href="" v-on:click="logout">Logout</a>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>

import EventBus from './EventBus'
import router from '../router'

EventBus.$on('logged-in', test => {
  console.log(test)
})
export default {
  data () {
    return {
      auth: '',
      user: ''
    }
  },

  methods: {
    logout () {
      localStorage.removeItem('usertoken')
      router.push({ path: '/Login' })
    }

  },
  mounted () {
    EventBus.$on('logged-in', status => {
      this.auth = status
    })
  }

}
</script>
