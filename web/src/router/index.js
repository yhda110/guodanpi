import Home from '../page/Home.vue'
import News from '../page/News.vue'
import Jock from '../page/Jock.vue'
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
		path: '/jock',
		name: 'Jock',
		component: Jock
	},
	{
		path: '*',
		component: News
	}
]
