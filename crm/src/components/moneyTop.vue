<template>
  <v-col cols="12" sm="8" md="4">
    <v-card>
      <v-card-actions>
        <div class="flex-grow-1"></div>
        <v-btn color="primary">登录</v-btn>
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

  }),
  methods:{
     getdata(){
         this.$axios.post('/api/msg/getCost',{
			}).then(res=>{
              console.log(res)

			})
	}


  },
  created(){
	  alert(5)
      this.getdata()
  }
};
</script>
