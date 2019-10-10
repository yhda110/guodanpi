import Vue from 'vue'
import './plugins/axios'
import App from './App.vue'
import vuetify from './plugins/vuetify';
import router from './router'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import store from './store'
import Toast from './plugins/toast'
Vue.config.productionTip = false
Vue.use(Toast)
new Vue({
  vuetify,
  router,
  store,
  render: h => h(App)
}).$mount('#app')
