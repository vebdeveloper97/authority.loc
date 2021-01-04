<template>
  <div id="default-layout">
    <navbar :user="user" />
    <router-view />
  </div>
</template>

<script>
import Navbar from "@/layouts/Navbar";

export default {
  name: "DefaultLayout",
  components: {Navbar},
  data(){
    return{
      user:{}
    }
  }
}
</script>

<style scoped>

</style>