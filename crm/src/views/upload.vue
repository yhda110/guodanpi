<template>
  <div>
    <v-container class="fill-witdh" fluid>
      <!-- <v-tabs >
          <v-tab v-for="item in tabslist" :key="item.id" @change="getnewdata(item.id)">{{item.name}}</v-tab>
      </v-tabs>-->
      <v-row align="center" justify="center">
        <v-col cols="16" sm="12" md="8">
          <v-card class="mx-auto" style="display:flex;flex-wrap:wrap;">
            <div style="padding: 10px 10px 0 0; box-sizing:border-box;">
              <input class="upload" @change="change" type="file" multiple accept="image/*" />
              <ul>
                <li v-for="item in newImage">
                  <img :src="item" alt />
                </li>
              </ul>
              <v-btn color="primary" :x-small="true" @click="submit">上传</v-btn>
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
      let images = this.images;
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
          console.log("file 转 base64结果：" + e.target.result);
          this.newImage.push(e.target.result);
        };
      }
      console.log(this.newImage);
    },
    submit(){
      if(this.newImage.length>0){
        var arr=[]
        for(let i=0;i<this.newImage.length;i++){
          if(i>=this.newImage.length){
               if(arr.includes(2)){
                 alert('上传失败')
               }else{
                 alert('上传成功')
               }
          }else{
            this.$axios.post("/api/image/postImage", {img:this.newImage[i]}).then(res=>{
                if(res.data.flag==true){
                    arr.push(1)
                }else{
                  arr.push(2)
                }
            })
          }
        }
      }
    }
  }
};
</script>
<style lang="scss">
</style>