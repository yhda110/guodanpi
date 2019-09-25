<template>
	<div>
		<van-nav-bar
			title="上传图片"
			left-text="返回"
			left-arrow
			right-text="上传"
			@click-left="back"
			@click-right="onClickRight"
		/>
		<div class="content_wrap">
			<van-field v-model="uploadData.title" placeholder="请输入标题" maxlength="500" />
			<van-field
				v-model="uploadData.desc"
				label="描述"
				type="textarea"
				placeholder="请输入留言"
				rows="5"
				autosize/>
			<van-uploader 
				:before-read="beforeRead" 
				:after-read="afterRead"
				v-model="fileList" 
				multiple/>
		</div>
	</div>
</template>
<script>
import {mapState, mapActions} from 'vuex'
import md5 from 'md5'
// import { Toast } from 'vant'
export default {
	data() {
		return {
			fileList: [{
				url: 'https://img.yzcdn.cn/vant/cat.jpeg',
				isImage: true
			}],
			uploadData: {
				title: '',
				desc: '',
				imgList: []
			},
			options: {}
		}
	},
	computed: {
		...mapState(['headerShow'])
	},
	mounted() {
		// Toast('xxxxxxxxxxxxxx')
		this.setState({key:'indexHeaderSHow',  value:false})
		// this.setState({key:'headerShow',  value:false})
	},
	methods: {
		...mapActions(['setState']),
		back() {
			this.$router.go(-1)
		},
		onClickRight() {

		},
		// 返回布尔值
    async beforeRead(file) {
			// let _this = this

      if (!(file.type === 'image/jpeg' || file.type === 'image/png')) {
				this.$Toast.fail({
					message: '请上传 jpg / png 格式图片'
				})
        return false
			}

			this.$Toast.loading({
				mask: true,
				message: '图片上传中...',
				duration: 0
			})
			let result = await this.$api('get', 'api/image/QiniuGetToken', {})
			var observer = {
				error(err){
					console.log(err)
				},
				complete(){
					_this.$Toast.clear({
						clearAll: true
					})
			
				}
			}
			this.$qiniu.compressImage(file, this.options).then(data => {
				let key = `${md5(new Date().getTime() + file.name)}.${file.type.split('/')[1]}`
				var observable = this.$qiniu.upload(data.dist, key, result.data.data.token)
				observable.subscribe(observer) // 上传开始
			})

			return true
    },
    // 返回 Promise
    // asyncBeforeRead(file) {
    //   return new Promise((resolve, reject) => {
    //     if (file.type !== 'image/jpeg') {
		// 			reject()
		// 			return false
    //     }
    //   })
		// },
		async afterRead() {
			// let _this = this
			// this.$Toast.loading({
			// 	mask: true,
			// 	message: '图片上传中...',
			// 	duration: 0
			// })

			// let result = await this.$api('get', 'api/image/QiniuGetToken', {})
			// let options = {}
			// var observer = {
			// 	error(err){
			// 		console.log(err)
			// 	},
			// 	complete(res){
			// 		_this.$Toast.clear({
			// 			clearAll: true
			// 		})
			// 		_this.uploadData.imgList.push(`${result.data.data.domain}/${res.key}`)
			// 	}
			// }
			// this.$qiniu.compressImage(file.file, options).then(data => {
			// 	let key = `${md5(new Date().getTime() + file.file.name)}.${file.file.type.split('/')[1]}`
			// 	var observable = this.$qiniu.upload(data.dist, key, result.data.data.token)
			// 	observable.subscribe(observer) // 上传开始
			// })
		}
	}
}
</script>