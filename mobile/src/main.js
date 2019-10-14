import Vue from 'vue'
import VueRouter from 'vue-router'
import routes from './router'
import store from './store'
import App from './App.vue'

import qs from 'qs'
import Axios from 'axios'
import Vant from 'vant'
import * as qiniu from 'qiniu-js'
import 'vant/lib/index.css'
import { Toast } from 'vant'

Vue.use(Toast);

Vue.prototype.$http = Axios
Vue.prototype.$qs = qs
Vue.prototype.$qiniu = qiniu
Vue.prototype.$Toast = Toast

Vue.prototype.$api = function (type, url, data) {
  return new Promise((resolve, reject) => {
    Axios[type](url,data).then(data => {
      resolve(data.data)
    }).catch(err => {
      reject(err)
    })
  })
}

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