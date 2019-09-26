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
			<van-field placeholder="请输入标题" maxlength="500" />
			<van-field
				v-model="message"
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
// import { Toast } from 'vant'
export default {
	data() {
		return {
			fileList: [{
				url: 'https://img.yzcdn.cn/vant/cat.jpeg',
				isImage: true
			}],
			message: '',
			uploadData: {
				title: 'sfsdafsdfdsf',
				desc: `dsfsdfdsdfgdfgdfg`,
				imgList: [
					'data:image/jpeg;base64,/9j/4RFiRXhpZgAATU0AKg',
					'data:image/jpeg;base64,/9j/4RFiRXhpZgAATU0AKg',
					'data:image/jpeg;base64,/9j/4RFiRXhpZgAATU0AKg',
					'data:image/jpeg;base64,/9j/4RFiRXhpZgAATU0AKg',
					'data:image/jpeg;base64,/9j/4RFiRXhpZgAATU0AKg',
				]
			}
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
    beforeRead(file) {
      if (!(file.type === 'image/jpeg' || file.type === 'image/png')) {
        // Toast('请上传 jpg 格式图片')
        return false
      }
      return true
    },
    // 返回 Promise
    asyncBeforeRead(file) {
      return new Promise((resolve, reject) => {
        if (file.type !== 'image/jpeg') {
          // Toast('请上传 jpg 格式图片')
          reject()
        } else {
          resolve()
        }
      })
		},
		async afterRead(file) {
			let result = await this.$api('get', 'api/image/QiniuGetToken', {})
			let options = {
				// quality: 0.92,
				// noCompressIfLarger: true
				// maxWidth: 1000,
				// maxHeight: 618
			}
			var putExtra = { 
				// fname: "",
				// params: {},
				// mimeType: [] || null
			}
			var config = {
				// useCdnDomain: true,
				// region: qiniu.region.z2
			}
			var observer = {
				// next(res){
				// 	console.log(res)
				// 	// ...
				// },
				error(err){
				    console.log('测试')
					// ...
				},
				complete(res){
					// ...
				}
			}
			this.$qiniu.compressImage(file.file, options).then(data => {
			    console.log(1)
				var observable = this.$qiniu.upload(data.dist, '12a3.jpg', result.data, putExtra, config)
				observable.subscribe(observer) // 上传开始
			})
		}
	}
}
</script>