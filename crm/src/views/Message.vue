<template>
    <v-container class="fill-witdh" fluid>
        <moneyTop ref="header" v-on:getmoney="getmoney"/>
        <v-tabs>
            <v-tab v-for="item in tabslist" :key="item.id" @change="getnewdata(item.id)">{{item.name}}</v-tab>
        </v-tabs>
        <v-simple-table>
            <template v-slot:default>
                <thead>
                <tr>
                    <th class="text-left">id</th>
                    <th class="text-left">手机号码</th>
                    <th class="text-left">ip</th>
                    <th style="width:200px;" class="text-left"><div style="width:300px;">内容</div></th>
                    <th class="text-left">验证码</th>
                    <th class="text-left">果丹皮订单号</th>
                    <th class="text-left">短信订单号</th>
                    <th class="text-left">错误码</th>
                    <th class="text-left">状态</th>
                    <th class="text-left">发送时间</th>
                    <th class="text-left">操作</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, key) in uploadList" :key="key">
                    <td>{{ item.id }}</td>
                    <td>{{ item.mobile }}</td>
                    <td>{{ item.ip }}</td>
                    <td>{{ item.content }}</td>
                    <td>{{ item.msg_code }}</td>
                    <td>{{ item.gdp_order_num }}</td>
                    <td>{{ item.dl_order_num }}</td>
                    <td>{{ item.error_num }}</td>
                    <td>{{ item.is_send_msg }}</td>
                    <td>{{ item.create_time }}</td>
                    <td class="text-center" v-if="item.is_send == 0">
                        <v-btn color="primary" :x-small="true"
                               @click="sendTwo(item)">重新发送
                        </v-btn>
                        <!-- <v-btn color="primary" x-small=true @click="login">登录</v-btn> -->
                    </td>
                    <td class="text-center" v-else>
                            <v-btn disabled color="primary"  @click="getmoney(item)" :x-small="true" >
                                发送成功
                            </v-btn>
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
    import moneyTop from '../components/moneyTop'

    export default {
        data() {
            return ({
                drawer: null,
                defaultType: '',
                page: 1,
                total: 0,
                limt: 10,
                tabs: '',//选中的tab
                uploadList: [],
                tabslist: [
                    {
                        id: '',
                        name: '全部'
                    },
                    {
                        id: '1',
                        name: '发送成功'
                    },
                    {
                        id: '0',
                        name: '发送失败'
                    },

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
        created() {
            this.getdata()
        },
        components: {
            moneyTop
        },
        methods: {
            getNext(number) {
                if (this.defaultType) {
                    this.getdata(number, this.defaultType)
                } else {
                    this.getdata(number)
                }
            },
            look(val) {
                this.$router.push({path: '/postsDetail', query: {id: val, type: this.defaultType}})
            },
            sendTwo(val) {
                this.$axios.post("/api/msg/sendFailMsg", {
                    mobile: val.mobile,
                    msg_id: val.id
                }).then(res => {
                    if (res.data.flag === true) {
                        this.$message.success(res.data.msg);
                        this.getdata(this.page, this.defaultType)
                    } else {
                        this.$message.error(res.data.msg)

                    }
                })
            },
            getmoney(){
                this.$refs.header.getmoney();
            },
            getnewdata(val) {
                this.defaultType = val
                this.page = 1
                if (val) {
                    this.tabs = val
                    this.getdata(1, val)
                } else {
                    this.tabs = ''
                    this.getdata(1)
                }
            },
            getdata(page) {
                this.$axios.post('/api/msg/getMsgList', {
                    limit: this.limt,
                    offset: page ? page - 1 : this.page - 1,
                    type: this.defaultType
                }).then(res => {
                    if (res.data.flag === true) {
                        this.uploadList = res.data.data.info
                        this.total = Math.ceil(Number(res.data.data.count) / this.limt)

                    } else {
                        this.uploadList = []
                    }

                })
            }
        }
    }
</script>