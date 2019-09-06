<template>
	<div class="list_wrap clearfix">
		<vue-waterfall-easy 
			:imgsArr="list" 
			@scrollReachBottom="getNewsList"
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
			param: {
				cid: 56,
				ext: 'house',
				token: 'c786875b8e04da17b24ea5e332745e0f',
				num: 10,
				// expIds: '20190106A13PFT%7C20190108A04MLS',
				page: 1,
				
			},
			loadingDotStyle: {
				backgroundColor: '#000'
			},
			list: []
		}
	},
	mounted() {
		this.setState({key: 'indexHeaderSHow', value: true})
		this.getNewsList()
		this.getList()

	},
	methods: {
		...mapActions(['setState']),
		getNewsList() {
			this.$http.get('/tencent/irs/rcd',{
				params: this.param
			}).then((response) => {
				let data = response.data.data
				this.param.page ++
				data.forEach(element => {
					this.list.push({
						...element,
						src: element.multi_imgs[0],
						href: '',
					})
				});
				// console.log(this.list)
			})
		},
		getList() {
			// this.$http.
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