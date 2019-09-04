<template>
	<div>
		<van-row>
			<van-col span="8">span: 8</van-col>
			<van-col span="8">span: 8</van-col>
			<van-col span="8">span: 8</van-col>
		</van-row>

		<van-row>
			<van-col span="4">span: 4</van-col>
			<van-col span="10" offset="4">offset: 4, span: 10</van-col>
		</van-row>

		<van-row>
			<van-col offset="12" span="12">offset: 12, span: 12</van-col>
		</van-row>
	</div>
</template>
<script>
export default {
	name: 'Home'
}
</script>