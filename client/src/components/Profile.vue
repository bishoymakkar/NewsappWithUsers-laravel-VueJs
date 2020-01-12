<template>
  <div class="container">
    <div class="jumbotron mt-5">
      <div class="col-sm-8 mx-auto">
        <h1 class="text-center">PROFILE</h1>
      </div>
      <table class="table col-md-6 mx-auto">
        <tbody>
          <tr>
            <td>Name</td>
            <td>{{whole_name}}</td>
          </tr>
          <tr>
            <td>Email</td>
            <td>{{email}}</td>
          </tr>
           <tr>
             <td>favourites</td>
            <td >
              <tr v-for="(item, index) in this.favourites" :key="index">
                <a v-bind:href="item.url" target="_blank">{{item.title}}</a>
                <button v-on:click="deleteFav(item)" class=" btn btn-danger " v-bind:style="{'width':'20%','height':'35px'}" >Delete</button>
              </tr>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  data () {
    this.getUser().then(res => {
      this.whole_name = res.user.name
      this.email = res.user.email
      this.favourites = res.user.favourites
      this.userid = res.user._id
      return res
    })
    return {
      whole_name: '',
      email: '',
      favourites: '',
      userid: ''
    }
  },
  methods: {
    getUser () {
      return axios.get('/api/profile', {
        headers: { Authorization: `Bearer ${localStorage.usertoken}` }
      }
      ).then(res => {
        console.log(res.data)
        return res.data
      })
        .catch(err => {
          console.log(err)
        })
    },
    deleteFav (ob) {
      if (confirm(`Are you sure to delete \n ${ob.title} ؟`)) {
        return axios.post('/api/removefav', {
          headline: ob,
          user_id: this.userid
        }).then(res => {
          this.favourites = res.data.favourites
          return res
        })
          .catch(err => {
            console.log(err)
          })
      }
    }
  }
}
</script>
