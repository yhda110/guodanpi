<template>
	<div>
		<van-nav-bar
			title="上传图片"
			left-text="返回"
			left-arrow
			right-text="上传"
			@click-left="onClickLeft"
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
import { Toast } from 'vant'
export default {
	data() {
		return {
			fileList: [{
				url: 'https://img.yzcdn.cn/vant/cat.jpeg',
				isImage: true
			}],
			message: ''
		}
	},
	computed: {
		...mapState(['headerShow'])
	},
	mounted() {
		Toast('xxxxxxxxxxxxxx')
		this.setState({key:'indexHeaderSHow',  value:false})
		this.setState({key:'headerShow',  value:false})
	},
	methods: {
		...mapActions(['setState']),
		onClickLeft() {

		},
		onClickRight() {

		},
		// 返回布尔值
    beforeRead(file) {
      if (file.type !== 'image/jpeg') {
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
		afterRead() {
			
		}
	}
}
</script>