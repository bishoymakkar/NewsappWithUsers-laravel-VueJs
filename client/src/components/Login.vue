<template>
  <div class="container">
    <div class="row">
      <div class="col-md-6 mt-5 mx-auto">
        <form v-on:submit.prevent="login">
          <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
          <div class="form-group">
            <label for="email">Email address</label>
            <input
              type="email"
              v-model="email"
              class="form-control"
              name="email"
              placeholder="Enter email"
            />
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input
              type="password"
              v-model="password"
              class="form-control"
              name="password"
              placeholder="Password"
            />
          </div>
          <button type="submit" class="btn btn-lg btn-primary btn-block">Sign in</button>
           <label style="color:blue" for="error">{{error}}</label>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import router from '../router'
import EventBus from './EventBus'
export default {
  data () {
    return {
      email: '',
      password: '',
      error: ''
    }
  },

  methods: {
    login () {
      axios
        .post('/api/login', {
          email: this.email,
          password: this.password
        })
        .then(res => {
          if (res.status === 200) {
            localStorage.setItem('usertoken', res.data.token)
            this.email = ''
            this.password = ''
            this.emitMethod()
            router.push({ name: 'Profile' })
          }
        })
        .catch(err => {
          if (err.response.status === 400) {
            this.error = 'Check email and password'
          }
        })
    },
    emitMethod () {
      EventBus.$emit('logged-in', 'loggedin')
    }
  }
}
</script>
