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
				<div class="list_item">
					<h3>xxxxxxxxxxasdasdasd但是犯得上发生</h3>
					<ul class="list_image clearfix">
						<li>
							<img src="https://p3.pstatp.com/list/pgc-image/RZxY7zaDbD0n8i" alt="">
						</li>
						<li>
							<img src="https://p3.pstatp.com/list/pgc-image/RZxY7zaDbD0n8i" alt="">
						</li>
						<li>
							<img src="https://p3.pstatp.com/list/pgc-image/RZxY7zaDbD0n8i" alt="">
						</li>
					</ul>
				</div>
			</van-list>
		</van-pull-refresh>
	</div>
</template>
<script>
// import { mapState, mapActions } from 'vuex'
export default {
	data () {
		return {
			page: 1,
			count: 10,
			newsList: [],
			listLoading: false,
			listFinished: true,
			refreshLoading: false,
			param: {
				cid: 56,
				ext: 'games',
				token: 'c786875b8e04da17b24ea5e332745e0f',
				num: 10,
				// expIds: '20190106A13PFT%7C20190108A04MLS',
				page: 1
			}
		}
	},
	computed: {
		// ...mapState(['count'])
	},
	mounted () {
		this.getNewsList()
	},
	methods: {
		// ...mapActions(['addFun', 'reductionFun']),
		getNewsList() {
			this.$http.get('/tencent/irs/rcd',{
				params: this.param
			}).then((response) => {
				this.newsList = this.newsList.concat(response.data.data)
				console.log(response.data)
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
				padding-left: .053333rem;
				width: 33.3%;
				display: inline-block;
				overflow: hidden;
				box-sizing: border-box;
				height: 1.96875rem;
				img{
					
				}
			}
		}
	}
}
</style>
