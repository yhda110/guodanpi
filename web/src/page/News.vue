<template>
	<div>
		<van-pull-refresh v-model="refreshLoading" @refresh="onRefresh">
			<van-list
				class="list_wrap"
				v-model="listLoading"
				:finished="listFinished"
				finished-text="没有更多了"
				@load="getNewsList"
			>
				<!-- <van-cell
					v-for="(item, index) in newsList"
					:key="index"
					:title="item.title"
				/> -->
				<div v-for="(item, index) in newsList" class="list_item img_1 img_3" :key="index">
					<h3>{{item.title}}</h3>
					<ul class="list_image clearfix">
						<li v-for="(img, key) in item.multi_imgs"  :key="key" :style="`background-image:url(${img})`">

						</li>
					</ul>
				</div>
			</van-list>
		</van-pull-refresh>
	</div>
</template>
<script>
import { mapState, mapActions } from 'vuex'
export default {
	data () {
		return {
			page: 1,
			listLoading: false,
			listFinished: true,
			refreshLoading: false,
			param: {
				cid: 56,
				ext: 'games',
				token: 'c786875b8e04da17b24ea5e332745e0f',
				num: 10,
				// expIds: '20190106A13PFT%7C20190108A04MLS',
				// page: 1
			}
		}
	},
	computed: {
		...mapState(['newsList'])
	},
	mounted () {
		this.getNewsList()
	},
	methods: {
		...mapActions(['pushList']),
		getNewsList() {
			this.$http.get('/tencent/irs/rcd',{
				params: {...this.param, page: 1}
			}).then((response) => {
				this.pushList({data: response.data.data,type: 'newsList'})
				// this.newsList = this.newsList.concat(response.data.data)
				console.log(this.newsList)
			})
		},
		onRefresh() {
      setTimeout(() => {
        this.$toast('刷新成功')
        this.refreshLoading = false
        // this.addFun()
      }, 500)
    }
	}
}
</script>
<style lang="less" scoped>
.list_wrap{
	.list_item{
		margin: 0 .4rem;
		min-height: 1.12rem;
		padding: .426667rem 0;
		h3{
			font-size: .453333rem;
		}
		.list_image{
			li{
				margin-left: .053333rem;
				width: 32%;
				display: inline-block;
				overflow: hidden;
				box-sizing: border-box;
				height: 1.96875rem;
				background: no-repeat center center / cover;
			}
		}
	}
}
</style>
