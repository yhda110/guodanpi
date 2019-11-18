<template>
    <div style="margin-bottom:10px;">
        <v-card>
            <v-card-actions>
                <div style="display:flex;justify-content:flex-end;width:100%;">
                    <span>已发送：<span style="color:#2196F3;">{{is_send_num}}</span>&nbsp;条</span>
                    <span style="margin-left:20px;">余额：<span style="color:red;">{{cost}}</span></span>
                    <!--<v-btn color="primary">发送条数：</v-btn>
                  <v-btn color="primary"></v-btn> -->
                </div>

            </v-card-actions>
        </v-card>
    </div>
</template>

<script>
    export default {
        props: {
            source: String
        },
        data: () => ({
            cost: 0,
            is_send_num: 0
        }),
        methods: {
            getdata() {
                this.$axios.post('/api/msg/getCost', {}).then(res => {
                    console.log(res)
                    this.cost = res.data.data.cost + "元"
                    this.is_send_num = res.data.data.count
                })
            },
            getmoney() {
                this.getdata();
            }

        },
        created() {
            this.getdata()
        }
    };
</script>
<style lang="stylus" scoped>

</style>
