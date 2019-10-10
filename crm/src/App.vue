<template>
  <v-app id="inspire">
    <router-view></router-view>
  </v-app>
</template>

<script>
  export default {
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