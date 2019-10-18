<template>
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
</template>
<script>
export default {
	data() {
		return ({
			show: false,
			index: 0,
			detailData: {
				img_list: []
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
	p{
		text-align: center;
	}
</style>
