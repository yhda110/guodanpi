import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    token: localStorage.getItem('token') ? localStorage.getItem('token') : ''
  },
  mutations: {
    changeLogin (state, user) {
      state.token = user.token;
      localStorage.setItem('token', user.token);
    }
  },
  actions: {

  }
})
