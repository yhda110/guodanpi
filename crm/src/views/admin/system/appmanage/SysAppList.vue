<template>
    <div class="main">
        <div ref="caimaomao"
             @click="handleClick"
        >
            呃，你好，果丹皮！
        </div>
    </div>
</template>
<script>
export default {
    name:'SysAppList.vue',
    data () {
      return {

      }
    },
    methods:{
        handleClick(){
            alert(this.$refs.caimaomao.innerHTML)
        }
    }
}
</script>
<style>

</style>
