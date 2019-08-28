import Vue from 'vue'
import VueRouter from 'vue-router'
import routes from './router'
import App from './App.vue'
import Vant from 'vant';
import 'vant/lib/index.css';

Vue.use(VueRouter)
Vue.use(Vant)
Vue.config.productionTip = false

const router = new VueRouter({
  mode: 'history',
  routes
})

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
