<template>
      <v-container class="fill-witdh" fluid>
        <!-- <v-tabs >
          <v-tab v-for="item in tabslist" :key="item.id" @change="getnewdata(item.id)">{{item.name}}</v-tab>
        </v-tabs> -->
        <v-simple-table>
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-left">id</th>
                <th class="text-left">姓名</th>
                <th class="text-left">性别</th>
                <th class="text-left">头像</th>
                <!-- <th class="text-left">内容</th> -->
                <th class="text-left">状态</th>
                <th class="text-left">操作</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, key) in uploadList" :key="key" >
                <td>{{ item.id }}</td>
                <td>{{ item.nickname }}</td>
                <td>{{ item.sex }}</td>
                <td>
					<img :src="item.pic_img" alt="">
				</td>
                <!-- <td>{{ item.content }}</td> -->
                <td>{{ item.status}}</td>
                <td class="text-center">
                     <v-btn color="primary" :x-small="true" style="margin-right:10px;" @click="deleteItem(item.id,1)">禁用</v-btn>
                     <v-btn color="primary" :x-small="true" @click="deleteItem(item.id,0)">解禁</v-btn>
                    <!-- <v-btn color="primary" x-small=true @click="login">登录</v-btn> -->
                </td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
        <div class="text-center">
          <v-pagination
            v-model="page"
            :length="total"
            @input="getNext(page)"
          ></v-pagination>
        </div>
      </v-container>
</template>
<script>
export default {
	data() {
		return ({
      drawer: null,
      defaultType:'',
      page: 1,
      total:0,
      limt:10,
      tabs:'',//选中的tab
      uploadList: [

      ],

		})
  },
  created(){
  this.getdata(0)
  },
	methods: {
    getNext(number){
          this.getdata(number-1)

    },
    deleteItem(val,type){
      this.$axios.post("/api/admin/user/updateUserStatus",{
         userid:val,
         status:type
       }).then(res=>{
		   if(res.data.flag==true){
			   this.$message.success(res.data.msg)
			   this.getdata()
		   }else{
             this.$message.error(res.data.msg)
		   }
       })
    },
    getdata(page){
      this.$axios.post('/api/admin/user/getUser',{
		  limit:this.limt,
		  page:page
	  }).then(res=>{
		  console.log(res)
           if(res.data.flag===true){
			  this.uploadList=res.data.data.data
			  console.log(this.uploadList)
              this.total=Math.ceil(Number(res.data.data.count)/this.limt)

           }

      })
    }
	}
}
</script>