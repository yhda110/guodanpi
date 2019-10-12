<template>
  <v-container class="fill-witdh" fluid>
    <v-row align="center" justify="center">
      <v-col cols="16" sm="12" md="8">
        <v-card class="mx-auto">
          <v-img v-for="(item,index) in condata.img_list" :src="item"  :key="index"></v-img>
          <v-card-title>
            <div>{{condata.title}}</div>
            <span class="grey--text subtitle-1" style="margin-left: 10px;">{{condata.nick_name}}</span>
            <div class="flex-grow-1"></div>
            <span class="grey--text subtitle-1" style="margin-left: 10px;">{{condata.create_time}}</span>
          </v-card-title>
          <v-expand-transition>
            <div>
              <v-card-text>{{condata.content}}</v-card-text>
            </div>
          </v-expand-transition>
          <v-card-actions>
            <div class="flex-grow-1"></div>
            <v-radio-group  :row="true" v-model="radioGroup">
              <v-radio
                v-for="item in lists"
                :key="item.id"
                :label="item.text"
                :value="item.id"
              ></v-radio>
            </v-radio-group>
          </v-card-actions>
          <v-card-actions>
            <div class="flex-grow-1"></div>
             <!-- <v-row> -->
                <v-btn color="primary"  style="margin-right:10px;" @click="ischeck(radioGroup)">确定</v-btn>
                <v-btn color="primary"  @click="back" >取消</v-btn>
             <!-- </v-row> -->
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
export default {
  data() {
    return {
      condata: {},
      success: 1,
      radioGroup: 1,
      id:'',
      type:'',
      lists:[
        {
          id:0,
          text:'等待审核 '
        },
        {
          id:1,
          text:'审核通过'
        },
        {
          id:2,
          text:'审核驳回'
        }]
    };
  },
  methods: {
    back(){
      this.$router.push({path:'/MainView'})
    },
    ischeck(val) {
       this.$axios.post("/api/admin/thread/publishThread",{
         thread_id:this.id,
         state:val
       }).then(res=>{
          if(res.data.flag===true){

           this.getdata(this.next_id,this.type)
          }
       })
    },
    getdata(val,type) {
      this.$axios
        .post("/api/admin/thread/getOneThread", {
          thread_id: val,
          is_published:type
        })
        .then(res => {
          if (res.data.flag === true) {
            this.condata = res.data.data;
            this.radioGroup=this.condata.is_published
            this.next_id=this.condata.next_id
          }
        })
        .catch(err => {
          console.log(err);
        });
    }
  },
  created() {
    this.id=this.$route.query.id
    this.type=this.$route.query.type
    this.getdata(this.$route.query.id,this.$route.query.type);
  }
};
</script>