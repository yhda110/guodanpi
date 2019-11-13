import Vue from 'vue'
import Router from 'vue-router'
const home = resolve => require(["@/views/Home"], resolve);
const login = resolve => require(["@/views/Login"], resolve);
const postsDetail = resolve => require(["@/views/PostsDetail"], resolve);
const MainView = resolve => require(["@/views/MainView"], resolve);
const tags = resolve => require(["@/views/tags"], resolve);
const message = resolve => require(["@/views/Message"], resolve);
const userList = resolve => require(["@/views/userList"], resolve);
const upload = resolve => require(["@/views/upload"], resolve);
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
          path: '/userList',
          name: 'userList',
          component: userList,
        },
        {
          path: '/MainView',
          name: 'MainView',
          component: MainView,
        },
        {
          path: '/postsDetail',
          name: 'postsDetail',
          component: postsDetail,
        },
        {
          path: '/tags',
          name: 'tags',
          component: tags,
        },
        {
          path: '/message',
          name: 'message',
          component:  message,
        },
        {
          path: '/upload',
          name: 'upload',
          component:  upload,
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