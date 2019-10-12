<template>
	<div class="list_wrap clearfix">
		<vue-waterfall-easy 
			:imgsArr="list" 
			@scrollReachBottom="getList"
			:isRouterLink="true"
			:loadingDotStyle="loadingDotStyle"
			:enablePullDownEvent="true"
			@pullDownMove="pullDownMove"
			@pullDownEnd="pullDownEnd"
		>
			<slot>
				<!-- <div>{{prop.src}}</div> -->
			</slot>
		</vue-waterfall-easy>
	</div>
</template>
<script>
import vueWaterfallEasy from 'vue-waterfall-easy'
import { mapActions } from 'vuex'
export default {
	name: 'Home',
	components: {
    vueWaterfallEasy
	},
	data() {
		return {
			listData: {
				// userid: '', //int 测试id：6（不传获取所有帖子）
				limit: 10, //int 长度（默认：10）
				offset: 1, //int 偏移量（默认：0）
				sort: 'desc', //string 'desc'：倒序 'asc'：正序
			},
			// param: {
			// 	cid: 56,
			// 	ext: 'house',
			// 	token: 'c786875b8e04da17b24ea5e332745e0f',
			// 	num: 10,
			// 	// expIds: '20190106A13PFT%7C20190108A04MLS',
			// 	page: 1,
			// },
			loadingDotStyle: {
				backgroundColor: '#d43d3d'
			},
			list: []
		}
	},
	mounted() {
		this.setState({key: 'indexHeaderSHow', value: true})
		this.getList()

	},
	methods: {
		...mapActions(['setState']),
		// 获取发帖列表
		async getList() {
			let result = await this.$api('post','/api/thread/getThread',this.listData)
			console.log(result)
			this.listData.offset ++
			result.data.info.forEach(element => {
				if(element.img_list[0]){
					this.list.push({
						...element,
						src: element.img_list[0],
						href: '',
					})
				}
			})
		},
		pullDownMove() {
			// console.log(2)
		},
		pullDownEnd() {
			console.log(3)
		}
	}
}
</script>
<style lang="less" scoped>
.list_wrap{
	// padding: .266667rem;
	position: absolute;
	top: 2.6rem;
	bottom: 1.173333rem;
	left: 0;
	right: 0;
}
// .vue-waterfall-easy-container .vue-waterfall-easy a{
	// width: 100%;
// }
</style>