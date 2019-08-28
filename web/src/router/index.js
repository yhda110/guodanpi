import Home from '../page/Home.vue'
import News from '../page/News.vue'
export default [
	{
		path: '/home',
		name: 'Home',
		component: Home
	},
	{
		path: '/news',
		name: 'News',
		component: News
	},
	{
		path: '*',
		component: Home
	}
]