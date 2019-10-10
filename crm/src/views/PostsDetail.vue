<template>
  <v-container class="fill-witdh" fluid>
    <v-row align="center" justify="center">
      <v-col cols="16" sm="12" md="8">
        <v-card class="mx-auto">
          <v-img v-for="item in condata.img_list" :src="item" height="200px"></v-img>
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
            <v-switch v-model="success" class="ma-2" @change="ispass" label="审核"></v-switch>
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
	  success: false,
    };
  },
  methods: {
	ispass(val){

	},
    getdata(val) {
      this.$axios
        .post("/api/admin/thread/getOneThread", {
          thread_id: val
        })
        .then(res => {
          if (res.data.flag === true) {
            this.condata = res.data.data;
          }
        })
        .catch(err => {});
    }
  },
  created() {
    this.getdata(this.$route.query.id);
  }
};
</script>