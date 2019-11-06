<template>
  <v-col cols="12" sm="8" md="4">
    <v-card>
      <v-card-actions>
          <v-btn color="primary">发送条数：{{is_send_num}}</v-btn>
          <v-btn color="primary">余额：{{cost}}</v-btn>
      </v-card-actions>
    </v-card>
  </v-col>
</template>

<script>
export default {
  props: {
    source: String
  },
  data: () => ({
      cost:0,
      is_send_num:0
  }),
  methods:{
     getdata(){
         this.$axios.post('/api/msg/getCost',{
			}).then(res=>{
              console.log(res)
                this.cost = res.data.data.cost+"元"
                this.is_send_num = res.data.data.count
			})
	}


  },
  created(){
      this.getdata()
  }
};
</script>
