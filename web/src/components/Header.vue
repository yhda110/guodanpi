<template>
	<div class="top_bar">
		<div class="title">
			<van-icon name="envelop-o" info="1" color="#fff" class="massage" size=".5rem"></van-icon>
			<div class="logo">
			</div>
			<van-icon name="search" color="#fff" class="search" size=".6rem"></van-icon>
		</div>
		<div class="tab_bar_wrap">
			<div class="tab_bar">
				<span class="active">推荐</span>
				<span>推荐</span>
				<span>推荐</span>
				<span>推荐</span>
				<span>推荐</span>
				<span>推荐</span>
				<span>推荐</span>
				<span>推荐</span>
				<span>推荐</span>
				<span>推荐</span>
			</div>
			<vav-icon class="add_tab_item" name="add-o" size=".5rem"></vav-icon>
		</div>
	</div>
</template>
<script>
export default {
	name: 'PageHeader'
}
</script>
<style lang="less" scoped>
.title {
	height: 1.173333rem;
	background-color: #d43d3d;
	position: relative;
	.massage {
		position: absolute;
		top: .333333rem;
		left: .533333rem;
	}
	.search {
		position: absolute;
		top: .333333rem;
		right: .533333rem;
	}
	.logo {
		width: 2.213333rem;
		height: 100%;
		background: url(https://s3b.pstatp.com/growth/mobile_list/image/wap_logo@3x_581de69e.png) no-repeat center center/ contain;
		margin: 0 auto;
	}
}
.tab_bar_wrap{
	height: .986667rem;
	background-color: #f4f5f6;
	overflow: hidden;
	position: relative;
	.tab_bar{
		width: 8.933333rem;
		font-size: .426667rem;
		overflow-x: auto;
		white-space: nowrap;
		padding-bottom: .533333rem;
		&>span{
			line-height: .693333rem;
			margin: .133333rem 0;
			padding: 0 .266667rem;
			display: inline-block;
			&.active{
				color: #d43d3d;
			}
		}
	}
	.add_tab_item{
		position: absolute;
		right: 0;
		top: 0;
	}
}
</style>