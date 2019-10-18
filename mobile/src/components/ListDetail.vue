<template>
	<van-image-preview
		v-model="show"
		:images="images"
		:z-index="9999"
		@change="onChange"
	>
		<template v-slot:index>第{{ index+1 }}页/共{{images.length}}页</template>
	</van-image-preview>
</template>
<script>
export default {
	data() {
		return ({
			show: false,
      index: 0,
      images: []
		})
	},
	methods: {
		async loadDetail(data) {
			console.log('xxxxxxxxxxx',data.value.id)
			let result = await this.$api('post','/api/admin/thread/getOneThread',{
				thread_id: data.value.id
			})
			console.log(result)
			this.images = result.data.img_list
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
</style>
