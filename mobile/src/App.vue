<template>
  <div id="app">
    <!-- <PageHeader v-show="true"></PageHeader> -->
    <IndexHeader v-show="indexHeaderSHow"></IndexHeader>
    <router-view></router-view>
    <PageFooter></PageFooter>  
  </div>
</template>

<script>
import IndexHeader from './components/IndexHeader'
// import PageHeader from './components/Header'
import PageFooter from './components/Footer'
import { mapState } from 'vuex'
export default {
  name: 'app',
  components: {
    IndexHeader,
    // PageHeader,
    PageFooter
  },
  computed: {
    ...mapState(['indexHeaderSHow'])
  },
  mounted() {
    this.getUserInfo()
  },
  methods: {
    getUserInfo() {
      // this.$http.get('https://www.lzjrys.store/wechat/wechatLogin').then((response) => {
      //   console.log(response)
      // })
    }
  }
}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  color: #2c3e50;
  position: relative;
  /* padding-top: 1.17333333rem; */
  /* top: 1.875rem; */
  /* bottom: 1.875rem; */
  touch-action: none;
  width: 100vw;
  height: 100vh;
  overflow: hidden;
}
</style>
