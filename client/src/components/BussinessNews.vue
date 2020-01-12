<template>
  <div>
    <!-- single root element here -->
    <div v-bind:style="{'display': 'inline'}" v-for="(item, index) in newsList" :key="index">
      <div
        class="col-md-3 col-sm-12"
        key="{lists.source.id}"
        v-bind:style="{
                       'flex':'1',
                       'display': 'inline-block',
                        'width': '18rem',
                        'height': '35rem',
                        'zIndex': '1',
                        'margin': '5px',
                        'marginTop': '70px',
                        'border': '1px dashed  #007bff',
                        'borderRadius': '10px'
                    }"
      >
        <img
          v-bind:src="item.urlToImage"
          class="card-img-top img-fluid"
          v-bind:style="{'height':'150px', 'object-fit': 'cover'}"
          alt="..."
        />
        <div class="card-body" v-bind:style="{ 'height': '100px' }">
          <h6 class="card-title">{{item.title}}</h6>
        </div>
        <ul class="list-group list-group-flush" v-bind:style="{ 'height': '40%' }">
          <li
            class="list-group-item text-right"
            v-bind:style="{ 'height': '30%' }"
          >{{item.source.name}}:المصدر</li>
          <li
            class="list-group-item text-right"
            v-bind:style="{ 'height': '30%' }"
          >التاريخ:{{item.publishedAt}}</li>
          <li
            class="list-group-item text-right"
            v-bind:style="{ 'height': '40%', 'overflow': 'scroll' }"
          >الوصف:{{item.description}}</li>
        </ul>
        <div class="card-body" style=" 'height': '20%'">
          <a v-bind:href="item.url" target="blank" class="card-link">Open article</a>
          <a
            v-on:click="updatefavourties(item)"
            v-bind:style="{'margin-left':'50px','color':'#0056b3'}"
            class="card-link"
          >
            <svg
              class="icon"
              v-bind:style="{ 'fill': '#0056b3','width':'10%','margin-left':'10px','margin-bottom':'5px'}"
              viewBox="0 0 24 24"
            >
              <path :d="svgPath" />
            </svg>
            Like
          </a>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Axios from 'axios'
import { mdiThumbUpOutline } from '@mdi/js'
import router from '../router'

export default
{
  data () {
    return {
      auth: '',
      whole_name: '',
      email: '',
      favourites: [],
      newsList: [],
      lists: {},
      id: '',
      userid: '',
      svgPath: mdiThumbUpOutline
    }
  },
  mounted () {
    Axios.get(
      'https://newsapi.org/v2/top-headlines?country=eg&category=business&apiKey=ea1e44d9aea24afa8058d766e39745bb'
    ).then((res) => {
      this.newsList = res.data.articles
    })
    Axios.get('/api/profile', {
      headers: { Authorization: `Bearer ${localStorage.usertoken}` }
    })
      .then(res => {
        this.userid = res.data._id
        return res.data
      }).then(res => {
        // for (let i = 0; this.newsList.length; i++) {
        //   Axios.post('/api/checkfav', {
        //     headline: this.newsList[i],
        //     user_id: this.userid
        //   }).then(res => {
        //     if (res.data === true) {
        //       this.newsList[i].liked = true
        //     }
        //   })
        // }
      })
      .catch(err => {
        console.log('not logged in')
        return err
      })
  },
  methods: {
    updatefavourties (ob) {
      return Axios.get('/api/profile', {
        headers: { Authorization: `Bearer ${localStorage.usertoken}` }
      })
        .then(res => {
          console.log(res.data)
          return res.data
        })
        .then(res => {
          if (res.user._id !== '') {
            this.userid = res.user._id
            return Axios.post('/api/updatefav', {
              headline: ob,
              user_id: this.userid
            })
              .then(res => {
                if (res.data === 'Match objects') {
                  alert('already added')
                } else {
                  alert(ob.title + 'added to favourites')
                }
                console.log(res.data)
                return res.data
              })
              .catch(err => {
                console.log(err)
              })
          }
        }).catch(err => {
          console.log(err)
          alert('Please Login!')
          router.push({ name: 'Login' })
        })
    }
  }
}
</script>
