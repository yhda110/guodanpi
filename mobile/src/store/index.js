import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)
export default new Vuex.Store({
	state: {
		indexHeaderSHow: true,
		headerShow: false,
		footerShow: true
	},
	mutations: {
		setState(state, data) {
			state[data.key] = data.value
		}
	},
	actions: {
		setState(context, data) {
			context.commit('setState', data)
		}
	}
})