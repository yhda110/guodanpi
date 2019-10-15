"use strict";

import Vue from 'vue';
import axios from "axios";
import router from '../router';
import {Message} from 'element-ui'


// import Toast from './toast'
// console.log(Toast)
// Vue.prototype.$toast= Toast;
// Full config:  https://github.com/axios/axios#request-config
// axios.defaults.baseURL = process.env.baseURL || process.env.apiUrl || '';
// axios.defaults.headers.common['Authorization'] = AUTH_TOKEN;
// axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
let config = {
  // baseURL: process.env.baseURL || process.env.apiUrl || ""
  // timeout: 60 * 1000, // Timeout
  // withCredentials: true, // Check cross-site Access-Control
};
// axios.defaults.withCredentials = true
const _axios = axios.create(config);

_axios.interceptors.request.use(
  function(config) {
    // Do something before request is sent
    // alert(localStorage.getItem('token'))

    if(localStorage.getItem('token')){
      config.headers['token'] = localStorage.getItem('token');

      // _axios.defaults.headers.commom['ctoken']=
      console.log(axios.defaults.headers.common)
  }
    return config;
  },
  function(error) {
    // Do something with request error
    return Promise.reject(error);
  }
);

// Add a response interceptor
_axios.interceptors.response.use(
  function(response) {
    if (response.data.code == 401){
      localStorage.clear()
      router.replace({
          path: '/loginin',
      })
    }else if(response.data.code==0){
      // Toast.show('hello world')
      // alert(1)

    }else{
      Message({
        type:'error',
        message:response.data.msg
      })
       router.replace({
          path: '/loginin',
      })
    }
    // Do something with response data
    return response;
  },
  function(error) {
    // Do something with response error
    return Promise.reject(error);
  }
);

Plugin.install = function(Vue, options) {
  options
  Vue.axios = _axios;
  window.axios = _axios;
  Object.defineProperties(Vue.prototype, {
    axios: {
      get() {
        return _axios;
      }
    },
    $axios: {
      get() {
        return _axios;
      }
    },
  });
};

Vue.use(Plugin)

export default Plugin;
