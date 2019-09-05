<template>
	<div class="wrap">
		<van-tabbar
			v-model="active"
			active-color="#07c160"
			inactive-color="#000"
		>
			<van-tabbar-item icon="home-o">标签</van-tabbar-item>
			<van-tabbar-item icon="search">标签</van-tabbar-item>
			<van-tabbar-item icon="search">标签</van-tabbar-item>
			<!-- <van-tabbar-item icon="freinds-o">标签</van-tabbar-item> -->
			<van-tabbar-item icon="setting-o">标签</van-tabbar-item>
		</van-tabbar>
	</div>
</template>
<script>
export default {
	name: 'PageFooter',
	data () {
		return {
			active: 2
		}
	}
}
</script>
<style scoped>
	.wrap{
		height: 1.173333rem;
		width: 100%;
		background: #ccc;
		position: absolute;
		bottom: 0;
		left: 0;
	}
</style>
