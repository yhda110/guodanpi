<template>
  <div id="app">
    <PageHeader></PageHeader>
    <router-view></router-view>
    <PageFooter></PageFooter>  
  </div>
</template>

<script>
import PageHeader from './components/Header'
import PageFooter from './components/Footer'

export default {
  name: 'app',
  components: {
    PageHeader,
    PageFooter
  }
}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  color: #2c3e50;
  position: relative;
  /* top: 1.875rem; */
  /* bottom: 1.875rem; */
}
</style>
