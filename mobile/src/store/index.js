import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)
export default new Vuex.Store({
	state: {
		count: 1,
		newsPageNum: 0,
		newsList: []
	},
	mutations: {
		add(state) {
			state.count = state.count + 1
		},
		reduction(state) {
			state.count = state.count - 1
		},
		pushList(state, data) {
			state[data.type] = state[data.type].concat(data.data)
		}
	},
	actions: {
		addFun(context) {
			context.commit('add')
		},
		reductionFun(context) {
			context.commit('reduction')
		},
		pushList(context, data) {
			context.commit('pushList', data)
		}
	}
})