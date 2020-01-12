<template>
  <div class="container">
    <div class="row">
      <div class="col-md-6 mt-5 mx-auto">
        <form v-on:submit.prevent="register">
          <h1 class="h3 mb-3 font-weight-normal">Register</h1>
          <div class="form-group">
            <label for="name">First name</label>
            <input
              type="text"
              v-model="first_name"
              class="form-control"
              name="first_name"
              placeholder="Enter your first name"
            />
          </div>
          <div class="form-group">
            <label for="name">Last name</label>
            <input
              type="text"
              v-model="last_name"
              class="form-control"
              name="last_name"
              placeholder="Enter your lastname name"
            />
          </div>
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
          <button type="submit" class="btn btn-lg btn-primary btn-block">Register!</button>
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
      first_name: '',
      last_name: '',
      email: '',
      password: '',
      favourites: [],
      error: ''
    }
  },

  methods: {
    register () {
      axios
        .post('/api/register', {
          name: this.first_name + '' + this.last_name,
          email: this.email,
          password: this.password,
          favourites: this.favourites
        })
        .then(res => {
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
              console.log(err)
            })
        })
        .catch(err => {
          if (
            err.response.data ===
            '{"email":["The email has already been taken."]}'
          ) {
            this.error = 'email is already token'
          } else {
            this.error = 'please fill all required fields'
          }
          console.log(err)
        })
    },
    emitMethod () {
      EventBus.$emit('logged-in', 'loggedin')
    }
  }
}
</script>
