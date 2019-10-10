<template>
  <v-app id="inspire">
    <MainView v-if="false" />
    <Login v-if="true"/>
  </v-app>
</template>

<script>
  import MainView from './views/MainView'
  import Login from './views/Login'
  export default {
    components: {
      MainView,
      Login
    },
    props: {
      // source: String,
    },
    data: () => ({
      
    }),
    created () { 
      this.$vuetify.theme.dark = true
    },
    
  }
</script>