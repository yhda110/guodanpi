
<template>
    <div class="wrap">
     <v-alert  v-if="show" color="blue" >
       {{text}}
    </v-alert>
    </div>

</template>
<style scoped>
    .wrap{
        position: fixed;
        left: 60%;
        bottom:50%;
        padding:0 20px;
        border-radius: 5px;
        height:30px;
        line-height:30px;
        transform: translate(-52%,50%);
        color:#fff;
        font-size:16px;
    }
    .fadein {
        animation: animate_in 0.35s;
    }
    .fadeout {
        animation: animate_out 0.35s;
        opacity: 0;
    }
    @keyframes animate_in {
        0% {
            opacity: 0;
        }
        100%{
            opacity: 1;
        }
    }
    @keyframes animate_out {
        0% {
            opacity: 1;
        }
        100%{
            opacity: 0;
        }
    }
</style>