<template>
  <v-app id="inspire">
    <v-content>
      <v-container
        class="fill-height"
        fluid
      >
        <v-row
          align="center"
          justify="center"
        >
          <v-col
            cols="12"
            sm="8"
            md="4"
          >
            <v-card class="elevation-12">
              <v-toolbar
                color="primary"
                dark
                flat
              >
                <v-toolbar-title>果丹皮后台管理系统</v-toolbar-title>
                <div class="flex-grow-1"></div>
              </v-toolbar>
              <v-card-text>
                <v-form>
                  <v-text-field
										:error-messages="useridError"
										v-model="userid"
                    label="用户名"
                    name="login"
                    prepend-icon="person"
                    type="text"
                  ></v-text-field>
                  <v-text-field
										:error-messages="pswordError"
                    id="password"
										v-model="psword"
                    label="密码"
                    name="password"
                    prepend-icon="lock"
                    type="password"
                  ></v-text-field>
                </v-form>
              </v-card-text>
              <v-card-actions>
                <div class="flex-grow-1"></div>
                <v-btn color="primary" @click="login">登录</v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
  import{ mapMutations } from 'vuex'
  export default {
    props: {
      source: String,
    },
    data: () => ({
			userid: 'gdp_59387412',
			useridError: '',
			psword: '000000',
			pswordError: '',
      drawer: null,
		}),
		watch:{
			userid(){
				if(this.userid.length > 16 || this.userid.length < 6) {
					this.useridError = '用户名位数6-16位'
				}else {
					this.useridError = ''
				}
			},
			psword() {
				if(this.psword.length > 8 || this.psword.length < 6) {
					this.pswordError = '密码位数6-8位'
				}else {
					this.pswordError = ''
				}
			}
		},
		methods: {
      ...mapMutations(['changeLogin']),
			login() {
				if(this.userid === '') {
					this.useridError = '请输入用户名'
					return false
				} else if(this.userid.length > 16 || this.userid.length < 6) {
					this.useridError = '用户名位数6-16位'
					return false
				}
				if(this.psword === '') {
					this.pswordError = '请输入用户名'
					return false
				} else if(this.psword.length > 8 || this.psword.length < 6) {
					this.pswordError = '密码位数6-8位'
					return false
        }
        this.useridError = ''
        this.pswordError = ''
        console.log(this.$axios)
        this.$axios.post('/api/admin/login',{
          username: this.userid,
          password: this.psword
        }).then(res=> {
          console.log(res.data)
        })
			}
		}
  }
</script>
