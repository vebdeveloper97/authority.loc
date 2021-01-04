<template>
  <div id="auth-layout">
    <router-view />
  </div>
</template>

<script>
import Navbar from "@/layouts/Navbar";
export default {
  name: "AuthLayout",
  components: {
    // eslint-disable-next-line vue/no-unused-components
    Navbar
  }
}
</script>

<style lang="scss" scoped>
  #auth-layout{
    padding: 50px;
  }
</style>