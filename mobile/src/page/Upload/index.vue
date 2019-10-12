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
				v-model="uploadData.content"
				label="描述"
				type="textarea"
				placeholder="请输入描述（可不填）"
				rows="5"
				autosize/>
			<van-uploader 
				:before-read="beforeRead" 
				:after-read="afterRead"
				:before-delete="fileDelete"
				v-model="fileList"
				:max-count="8"
				:max-size="5000000"
				:delete="false"
				multiple/>
		</div>
	</div>
</template>
<script>
import {mapState, mapActions} from 'vuex'
import md5 from 'md5'
export default {
	data() {
		return {
			fileList: [],
			uploadData: {
				title: '测试',
				content: '我是测试内容我是测试内容我是测试内容我是测试内容我是测试内容我是测试内容我是测试内容我是测试内容我是测试内容',
				imglist: []
			},
			options: {}
		}
	},
	computed: {
		...mapState(['headerShow'])
	},
	mounted() {
		this.setState({key:'indexHeaderSHow', value:false})
	},
	methods: {
		...mapActions(['setState']),
		back() {
			this.$router.go(-1)
		},
		onClickRight() {
			this.submit()
		},
		// async beforeRead(file) {
    //   if (file.type !== 'image/jpeg') {
    //     // Toast('请上传 jpg 格式图片');
    //     return false;
    //   }
    //   return true;
    // },
		// 返回布尔值
		beforeRead(file) {
      if (!(file.type === 'image/jpeg' || file.type === 'image/png')) {
				this.$Toast.fail({message: '请上传 jpg / png 格式图片'})
        return false
			}
			return true
		},
		async afterRead({file}) {
			let _this = this
			this.$Toast.loading({
				mask: true,
				message: '图片上传中...',
				duration: 0
			})
			let result = await this.$api('get', '/api/image/QiniuGetToken', {})
			await new Promise((resolve, reject) => {
				this.$qiniu.compressImage(file, this.options).then(data => {
					let key = `${md5(new Date().getTime() + file.name)}.${file.type.split('/')[1]}`
					var observable = this.$qiniu.upload(data.dist, key, result.data.data.token)
					observable.subscribe({
							error(err){
								console.log(err)
								_this.fileList.pop()
								_this.$Toast.fail({message: '上传失败'})
								reject(err)
							},
							complete(res){
								_this.$Toast.clear({
									clearAll: true
								})
								let url = `${result.data.data.domain}/${res.key}`
								_this.uploadData.imglist.push(url)
								resolve()
							}
						}) // 上传开始
				})
			})
		},
		fileDelete(file,detail) {
			console.log(file)
			console.log(detail)
			return false
		},
		// 上传发帖
		async submit() {
			// 检测完整性
			if(!this.uploadData.title) {
				this.$Toast.fail({message: '请填写标题'})
				return false
			}else if(this.uploadData.imglist.length === 0) {
				this.$Toast.fail({message: '请插入图片'})
				return false
			}
			let reqData = {...this.uploadData, userid: '6'}
			reqData.imglist = reqData.imglist.join()
			let result = await this.$api('post', '/api/thread/upload', reqData)	
			if(result.code === 0){
				this.$Toast.success({message: '发布成功，请耐心等待审核'})
				setTimeout(() => {
					this.$router.push('/')
				}, 500);
			}
		}
	}
}
</script>