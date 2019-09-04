import Vue from 'vue'
import VueRouter from 'vue-router'
import routes from './router'
import store from './store'
import App from './App.vue'

import qs from 'qs'
import Axios from 'axios'
import Vant from 'vant'
import 'vant/lib/index.css'

Vue.prototype.$http = Axios
Vue.prototype.$qs = qs

Vue.use(VueRouter)
Vue.use(Vant)
Vue.config.productionTip = false

const router = new VueRouter({
  mode: 'history',
  routes
})

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
