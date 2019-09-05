<template>
<div>xxxxxxxxxxx</div>
</template>
<script>
import {mapState, mapActions} from 'vuex'
export default {
	data() {
		return {

		}
	},
	computed: {
		...mapState(['headerShow'])
	},
	mounted() {
		this.setState({key:'indexHeaderSHow',  value:false})
		this.setState({key:'headerShow',  value:false})
	},
	methods: {
		...mapActions(['setState'])
	}
}
</script>