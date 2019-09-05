import Home from '../page/Home/index.vue'
import News from '../page/News/index.vue'
// import Jock from '../page/Jock/index.vue'
import Upload from '../page/Upload/index.vue'
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
		path: '/upload',
		name: 'Upload',
		component: Upload
	},
	{
		path: '*',
		name: 'not found',
		component: Home
	}
]
