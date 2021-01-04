<template>
  <div class="navbar">
    Welcome {{user.username}}
    <a href="" @click="logout">Logout</a>
  </div>
</template>

<script>
export default {
  name: "Navbar",
  props: {
    user:{
      type: Object
    }
  },
  methods: {
    async logout(){
      console.log('salom')
      this.$router.push({name: 'Login'});
      localStorage.clear();
    }
  }
}
</script>

<style lang="scss" scoped>
  .navbar{
    height: 50px;
    position: fixed;
    left: 0;
    top: 0;
    right: 0;
    line-height: 50px;
    background: #1f5d6b;
    color: #fff;
    padding: 0 15px;
    text-align: right;
    transition: font-weight 0.3s;
  }

</style>