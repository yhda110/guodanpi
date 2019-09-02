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
				<span 
					:class="{'active': item.routerLink === selectTab}" 
					:key="index" 
					v-for="(item ,index) in tabList" 
					@click="tebTap(item.routerLink)">{{item.name}}</span>
			</div>
			<div class="list_shadow"></div>
			<van-icon name="add-o" color="#d43d3d" class="add_tab_item" size="0.5rem"></van-icon>
		</div>
	</div>
</template>
<script>
export default {
	name: 'PageHeader',
	data() {
		return {
			selectTab: 'jock',
			tabList: [
				{name: '推荐', routerLink: 'jock'},
				{name: '新闻', routerLink: 'news'},
				{name: '热点', routerLink: '/1'},
				{name: '社会', routerLink: '/2'},
				{name: '娱乐', routerLink: '/3'},
				{name: '军事', routerLink: '/4'},
				{name: '科技', routerLink: '/5'},
				{name: '汽车', routerLink: '/6'},
				{name: '房产', routerLink: '/7'},
				{name: '家居', routerLink: '/8'},
				{name: '体育', routerLink: '/9'},
				{name: '财经', routerLink: '/0'},
			]
		}
	},
	mounted() {
		this.tebTap('news')
	},
	methods: {
		tebTap(routerLink) {
			this.selectTab = routerLink
			this.$router.push({
				path: `/${routerLink}`
			})
		}
	}
}
</script>
<style lang="less" scoped>
.top_bar{
	position: fixed;
	top: 0;
	left: 0;
	z-index: 9999;
	width: 100%;
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
			background: url(../assets/wap_logo@3x_581de69e.png) no-repeat center center/ contain;
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
		.list_shadow{
			background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAABXCAQAAACjUt0DAAAAAmJLR0QA/4ePzL8AAAAJcEhZcwAAAEgAAABIAEbJaz4AAAAJdnBBZwAAABIAAABXAK421QoAAAFiSURBVEjH1ZbbtoMgDERnKP//yaYPVSAhF077dHQtamU7k0RKAxwcPCF4ItF+tWMNsVZS91uJhNlRX7FGPMgizOrEObJSiZUUAnRkR6q0qnErm7Lhc9UDBaXUt0kN0FOaOo4SQ5BRCahOA3HT5KwTE50GZhV37VazJKYljjE2SE+sPsittC1XW/FmgNVsZJcvuhvrAbLqffNadPXLYgaLzs8wWSo6rk9FYdeBUW/x1DRswfS6ysuNlcDZ7ssTSCpIfDsZn/J8b+Omh8pjR/X8DkuWndwnINpOlukFbtvzVslAEmF+TCMaCC4fkr8oKUgVzmQ4IBojHfqFqwd28O0S6AXA7ib2P+HOTlzLcXZ3+tFz6yRObiOmGY+3k6ev5XN97Uq7FoCXkl5HTrT6tQiw2u1aY/TrZF551jyMFPZiOqW1O5zXiJx1PV/2Tw52tD8ddYc/9Zlq7qhBrlrtuhX/98cbycGBFpNaiv0AAAAldEVYdGRhdGU6Y3JlYXRlADIwMTQtMDItMjdUMTc6NDQ6NDErMDg6MDDFbrv7AAAAJXRFWHRkYXRlOm1vZGlmeQAyMDE0LTAyLTI3VDE3OjQ0OjQxKzA4OjAwtDMDRwAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAAASUVORK5CYII=') no-repeat center right / contain;
			position: absolute;
			right: .933333rem;
			top: 0;
			width: .666667rem;
			height: 1rem;
		}
		.add_tab_item{
			position: absolute;
			right: .2rem;
			top: .2rem;
		}
	}
}
</style>