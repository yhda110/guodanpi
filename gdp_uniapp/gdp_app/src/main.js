import Vue from 'vue'
import App from './App'

Vue.config.productionTip = false

Vue.prototype.$host = 'https://unidemo.dcloud.net.cn/';
// Vue.prototype.$base_url = 'http://test.lzjrys.store/'; //测试
Vue.prototype.$base_url = 'https://www.lzjrys.store/'; //线上
App.mpType = 'app'

const app = new Vue({
    ...App
})
app.$mount()
