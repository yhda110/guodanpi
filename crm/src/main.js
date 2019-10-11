import Vue from 'vue'
import './plugins/axios'
import App from './App.vue'
import vuetify from './plugins/vuetify';
import router from './router'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import store from './store'
import toastRegistry from './plugins/toast'
Vue.use(toastRegistry)
Vue.config.productionTip = false
new Vue({
  vuetify,
  router,
  store,
  render: h => h(App)
}).$mount('#app')
