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
				placeholder="请输入描述（可不填）"
				rows="5"
				autosize/>
			<van-uploader 
				:before-read="beforeRead" 
				:after-read="afterRead"
				@delete="fileDelete"
				v-model="fileList"
				:max-count="6"
				:max-size="5000000"
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
			fileList: [],
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
	},
	methods: {
		...mapActions(['setState']),
		back() {
			this.$router.go(-1)
		},
		onClickRight() {
			this.submit()
		},
		// 返回布尔值
    async beforeRead(file) {
			let _this = this

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
			await new Promise((resolve, reject) => {
				this.$qiniu.compressImage(file, this.options).then(data => {
					let key = `${md5(new Date().getTime() + file.name)}.${file.type.split('/')[1]}`
					var observable = this.$qiniu.upload(data.dist, key, result.data.data.token)
					observable.subscribe({
							error(err){
								console.log(err)
								reject(err)
							},
							complete(res){
								_this.$Toast.clear({
									clearAll: true
								})
								let url = `${result.data.data.domain}/${res.key}`
								_this.uploadData.imgList.push(url)
								resolve()
							}
						}) // 上传开始
				})
			})
			return true
		},
		fileDelete(file) {
			console.log(file)
		},
		afterRead() {},
		// 上传发帖
		submit() {
			// 检测完整性
			console.log(this.fileList)
			// console.log(this.uploadData.desc)
			console.log(this.uploadData.title)
			if(!this.uploadData.title) {
				this.$Toast.fail({message: '请填写标题'})
				return false
			}
		}
	}
}
</script>