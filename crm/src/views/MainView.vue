<template>
      <v-container class="fill-witdh" fluid>
        <v-tabs >
          <v-tab v-for="item in tabslist" :key="item.id" @change="getnewdata(item.id)">{{item.name}}</v-tab>
        </v-tabs>
        <v-simple-table>
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-left">id</th>
                <th class="text-left">发布时间</th>
                <th class="text-left">标题</th>
                <th class="text-left">发帖人</th>
                <!-- <th class="text-left">内容</th> -->
                <th class="text-left">状态</th>
                <th class="text-left">操作</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, key) in uploadList" :key="key" >
                <td>{{ item.id }}</td>
                <td>{{ item.create_time }}</td>
                <td>{{ item.title }}</td>
                <td>{{ item.nick_name }}</td>
                <!-- <td>{{ item.content }}</td> -->
                <td>{{ item.is_published_text }}</td>
                <td class="text-center">
                     <v-btn color="primary" :x-small="true" v-if="item.is_del==0" style="margin-right:10px;" @click="deleteItem(item.id,1)">删除</v-btn>
                       <v-btn color="primary" :x-small="true" v-else style="margin-right:10px;" @click="deleteItem(item.id,0)">恢复</v-btn>
                     <v-btn color="primary" :x-small="true" @click="look(item.id)" >查看</v-btn>
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
      tabslist:[
        {
          id:'',
          name:'全部'
        },
        {
          id:'0',
          name:'待审核'
        },
        {
          id:'1',
          name:'已通过'
        },
       {
          id:'2',
          name:'已驳回'
        },
        {
          id:'3',
          name:'已删除'
        }



      ],
			// slidebarData: [
      //   { icon: 'contacts', text: 'Contacts' },
      //   { icon: 'history', text: 'Frequently contacted' },
      //   { icon: 'content_copy', text: 'Duplicates' },
      //   {
      //     icon: 'keyboard_arrow_up',
      //     'icon-alt': 'keyboard_arrow_down',
      //     text: 'Labels',
      //     model: true,
      //     children: [
      //       { icon: 'add', text: 'Create label' },
      //     ],
      //   },
      //   {
      //     icon: 'keyboard_arrow_up',
      //     'icon-alt': 'keyboard_arrow_down',
      //     text: 'More',
      //     model: false,
      //     children: [
      //       { text: 'Import' },
      //       { text: 'Export' },
      //       { text: 'Print' },
      //       { text: 'Undo changes' },
      //       { text: 'Other contacts' },
      //     ],
      //   },
      //   { icon: 'settings', text: 'Settings' },
      //   { icon: 'chat_bubble', text: 'Send feedback' },
      //   { icon: 'help', text: 'Help' },
      //   { icon: 'phonelink', text: 'App downloads' },
      //   { icon: 'keyboard', text: 'Go to the old version' },
      // ],
		})
  },
  created(){
  this.getdata()
  },
	methods: {
    getNext(number){
      if(this.defaultType){
          this.getdata(number,this.defaultType)
      }else{
          this.getdata(number)
      }
    },
    look(val){
       this.$router.push({path:'/postsDetail',query:{id:val,type:this.defaultType}})
    },
    deleteItem(val,type){
      this.$axios.post("/api/admin/thread/publishThread",{
         thread_id:val,
         is_del:type
       }).then(res=>{
          if(res.data.flag===true){
            this.$message.success(res.data.msg)
            this.getdata(this.page,this.defaultType)
          }else{
            this.$message.error(res.data.msg)

          }
       })
    },
    getnewdata(val){
      this.defaultType=val
      this.page=1
       if(val){
         this.tabs=val
        this.getdata(1,val)
       }else{
         this.tabs=''
        this.getdata(1)
       }
    },
    getdata(page,type){
      this.$axios.post('/api/admin/thread/getThread',{
        userid:'',
        limit:this.limt,
        offset:page?page-1:this.page-1,
        sort:'',
        is_del:type==3?1:'',
        is_published:type?(type==3?'':type):'',
      }).then(res=>{
           if(res.data.flag===true){
              this.uploadList=res.data.data.info
              this.total=Math.ceil(Number(res.data.data.count)/this.limt)

           }else{
             this.uploadList=[]
           }

      })
    }
	}
}
</script>