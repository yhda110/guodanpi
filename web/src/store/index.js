import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)
export default new Vuex.Store({
	state: {
		count: 1
	},
	mutations: {
		add(state) {
			state.count = state.count + 1
		},
		reduction(state) {
			state.count = state.count - 1
		}
	},
	actions: {
		addFun(context) {
			context.commit('add')
		},
		reductionFun(context) {
			context.commit('reduction')
		}
	}
})