<template>
	<div class="main">
		<van-image-preview
			v-model="show"
			:images="detailData.img_list"
			:z-index="9999"
			@change="onChange"
		>
			<template v-slot:index>
				<p>{{detailData.title}}</p>
				<p>{{ index+1 }}/{{detailData.img_list.length}}</p>
			</template>
		</van-image-preview>
		<div class="bottomCard" v-show="show">
			我这里是卡片位置
		</div>
	</div>
</template>
<script>
export default {
	data() {
		return ({
			show: true,
			index: 0,
			detailData: {
				img_list: ["https://lzjrys.store/8b81247f5954467bea17b420c7378aed.jpeg","https://lzjrys.store/67e5022ed45ca6e331dea37e742dfc20.png"],
				title: '测试',
				content: '我是测试内容我是测试内容我是测试内容我是测试内容我是测试内容我是测试内容我是测试内容我是测试内容我是测试内容',
				create_time: '2019-09-26 09:58:03'
			}
		})
	},
	methods: {
		async loadDetail(data) {
			let result = await this.$api('post','/api/admin/thread/getOneThread',{
				thread_id: data.value.id
			})
			this.detailData = result.data
			this.index = 0
			this.show = true
		},
		onChange(index) {
      this.index = index
    }
	}
}
</script>
<style lang="less" scoped>
	.main{
		.bottomCard{
			background: #fff;
			position: fixed;
			height: 1.333333rem;
			width: 100vw;
			bottom: 0;
			left: 0;
			z-index: 99999;
		}
	}
</style>
