import Vue from 'vue'
import Router from 'vue-router'
import home from './views/Home.vue'
import login from './views/Login.vue'
import MainView from './views/MainView.vue'
import postsDetail from './views/PostsDetail.vue'
Vue.use(Router)
const router = new Router({
  routes: [
    {
      path: '/',
      redirect: '/login'
    },
    {
      path: '/login',
      name: 'login',
      component: login
    },
    {
      path: '/home',
      name: 'home',
      component: home,
      children:[
        {
          path: '/MainView',
          name: 'MainView',
          component: MainView,
        },
        {
          path: '/postsDetail',
          name: 'postsDetail',
          component: postsDetail,
        }
      ]
    }
  ]
});

router.beforeEach((to, from, next) => {
  if (to.path === '/login') {
    next();
  } else {
    let token = localStorage.getItem('token');

    if (token === null || token === '') {

      next({path: "/login"})

    } else {
      next();
    }
  }
});
export default router;