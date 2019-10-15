<template>
	<van-popup 
		class="pop" 
		v-model="show" 
		get-container="#app" 
		:lock-scroll=true
	/>
</template>
<script>
export default {
	data() {
		return ({
			show: true
		})
	}
}
</script>
<style lang="less" scoped>

</style>
