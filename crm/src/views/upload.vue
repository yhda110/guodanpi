<template>
  <div>
    <v-container class="fill-witdh" fluid>
      <!-- <v-tabs >
          <v-tab v-for="item in tabslist" :key="item.id" @change="getnewdata(item.id)">{{item.name}}</v-tab>
      </v-tabs>-->
      <v-row align="center" justify="center">
        <v-col cols="16" sm="12" md="8">
          <v-card class="mx-auto" style="display:flex;flex-wrap:wrap;">
            <div>
              <div class="imgTop">
                <div class="imgbtn" >
                  <div class="uploadTop">
                    <img src="../assets/defalut.png" alt />
                    <span>选取文件</span>
                  </div>
                  <input class="upload" @change="change" type="file" multiple accept="image/*" />
                </div>
                <v-btn color="primary" :x-small="true" @click="submit">上传照片</v-btn>
              </div>
              <ul>
                <li style="margin-bottom:10px;" v-for="(item,index) in newImage" :key="index">
                  <div class="imgBox">
                    <div class="btns" v-clipboard:copy="item.img" v-clipboard:success="onCopy" v-clipboard:error="onError" v-if="item.isshow&&item.status=='success'">复制</div>
                    <img :src="item.img" alt />
                    <div class="progress" v-if="item.isshow">
                      <el-progress type="circle"  :status="item.status" :percentage="item.progress"></el-progress>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
            <!-- <v-card-actions>
            <div class="flex-grow-1"></div>-->
            <!-- <v-row> -->
            <!-- <v-btn color="primary"  style="margin-right:10px;" @click="ischeck(radioGroup)">确认</v-btn>
            <v-btn color="primary"  @click="back" >取消</v-btn>-->
            <!-- </v-row> -->
            <!-- </v-card-actions> -->
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>
<script>
export default {
  data: () => ({
    images: [],
    filesArr: [],
    newImage: []
  }),
  methods: {
    onCopy(e){
     alert('复制成功！')
    },
    onError(e){
      alert('抱歉，该浏览器不支持复制！')
    },
    change(e) {
      let files = e.target.files;
      console.log(files);
      // 如果没有选中文件，直接返回
      if (files.length === 0) {
        return;
      }
      if (this.images.length + files.length > this.maxCount) {
        //   Toast('最多只能上传' + this.maxCount + '张图片！');
        return;
      }
      let reader;
      let file;
      for (let i = 0; i < files.length; i++) {
        file = files[i];
        this.filesArr.push(file);
        reader = new FileReader();
        // if (file.size > self.maxSize) {
        //   // Toast('图片太大，不允许上传！');
        //   continue;
        // }
        reader.readAsDataURL(file);
        reader.onload = e => {
          let obj = {
            img: e.target.result,
            progress: 0,
            status: "",
            isshow:false
          };
          this.newImage.push(obj);
        };
      }
      console.log(this.newImage);
    },
    async submit() {
      // var arr = [1, 2, 3, 4, 5];
      // const getnum = num => {
      //   return new Promise(resolve => setTimeout(resolve(num), 5000));
      // };
      // const forloop = async () => {
      //   console.log("start");
      //   for (let i = 0; i < arr.length; i++) {
      //     const num = await getnum(arr[i]);
      //     console.log(num);
      //   }
      //   console.log("end");
      // };
      // forloop();
      // this.newImage=this.newImage.reverse()
      if (this.newImage.length > 0) {
        for (let i = 0; i < this.newImage.length; i++) {

            await this.uploadimg(this.newImage[i].img,i)
        }
      }
    },
   uploadimg(val,index) {
     this.newImage[index].isshow=true
      this.$axios.post("/api/image/postImage", { img: val })
        .then(res => {
          if (res.data.flag == true) {
            this.newImage[index].progress=100;
             this.newImage[index].status='success'
             this.newImage[index].img=res.data.data.url
          } else {
            this.newImage[index].status='exception'
          }
        });
      // this.newImage[index].isshow=false
    }
  }
};
</script>
<style lang="scss">
.imgTop {
  display: flex;
  align-items: center;
  .imgbtn {
    width: 200px;
    height: 30px;
    position: relative;
    margin-right: 20px;
    .upload {
      position: absolute;
      top: 0;
      left: 0;
      z-index: 1;
      display: block;
      width: 200px;
      height: 30px;
      opacity: 0;
    }
    .uploadTop {
      width: 200px;
      height: 30px;
      position: absolute;
      top: 0;
      left: 0;
      z-index: 0;
      display: flex;
      align-items: center;
      // background:#fff;
      img {
        width: 16px;
        height: 16px;
        margin: 0 10px;
      }
      span {
        color: #999;
        font-size: 16px;
      }
    }
  }
}

.imgBox {
  width: 200px;
  height: 200px;
  position: relative;
  .btns{
    width: 60px;
    height: 20px;
    border-radius: 20px;
    text-align: center;
    line-height: 20px;
    background: #2196F3;
    font-size: 12px;
    color:#fff;
    position: absolute;
    top: 2px;
    left: 2px;
    z-index: 3;
    cursor: pointer;
  }
  img {
    width: 200px;
    height: 200px;
    position: absolute;
    border-radius: 8px;
    top: 0;
    left: 0;
    z-index: 0;
  }
  .progress {
    position: absolute;
    border-radius: 8px;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    background: rgba(0, 0, 0, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
  }
}
</style>