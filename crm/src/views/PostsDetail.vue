<template>
  <v-container class="fill-witdh" fluid>
    <v-row align="center" justify="center">
      <v-col cols="16" sm="12" md="8">
        <v-card class="mx-auto">
          <v-img v-for="(item,index) in condata.img_list" :src="item" height="200px" :key="index"></v-img>
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
                 @change="ischeck(item.id)"
              ></v-radio>
            </v-radio-group>
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
    ischeck(val) {
       this.$axios.post("/api/admin/thread/publishThread",{
         thread_id:this.id,
         state:val
       }).then(res=>{
          console.log(res)
       })
    },
    getdata(val) {
      this.$axios
        .post("/api/admin/thread/getOneThread", {
          thread_id: val
        })
        .then(res => {
          if (res.data.flag === true) {
            this.condata = res.data.data;
            this.radioGroup=this.condata.is_published
          }
        })
        .catch(err => {
          console.log(err);
        });
    }
  },
  created() {
    this.id=this.$route.query.id
    this.getdata(this.$route.query.id);
  }
};
</script>