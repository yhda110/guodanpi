<template>
	<div>
		xxx
	</div>
</template>
<script>
export default {
	data () {
		return {
			pageNum: 1,
			pageCount: 10,
			pageType: 'text',
			newsList: []
		}
	},
	mounted () {
		this.getNewsList()
	},
	methods: {
		getNewsList() {
			this.$http.get('https://api.apiopen.top/getJoke',{
				params: {
					page: this.pageNum,
					count: this.pageCount,
					type: this.pageType
				}
			}).then((response) => {
				console.log(response.data.result)
			})
		}
	}
}
</script>
