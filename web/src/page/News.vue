<template>
	<div>
		<van-pull-refresh v-model="refreshLoading" @refresh="onRefresh">
			<van-list
				v-model="listLoading"
				:finished="listFinished"
				finished-text="没有更多了"
				@load="getNewsList"
			>
				<van-cell
					v-for="item in newsList"
					:key="item"
					:title="item"
				/>
			</van-list>
		</van-pull-refresh>
	</div>
</template>
<script>
import { mapState, mapActions } from 'vuex'
export default {
	data () {
		return {
			pageNum: 1,
			pageCount: 10,
			pageType: 'text',
			newsList: [],
			listLoading: false,
			listFinished: true,
			refreshLoading: false
		}
	},
	computed: {
		...mapState(['count'])
	},
	mounted () {
		this.getNewsList()
	},
	methods: {
		...mapActions(['addFun', 'reductionFun']),
		getNewsList() {
			
		},
		onRefresh() {
      setTimeout(() => {
        this.$toast('刷新成功')
        this.refreshLoading = false
        this.addFun()
      }, 500)
    }
	}
}
</script>
<style lang="less" scoped>

</style>
