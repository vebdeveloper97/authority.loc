<template>
  <div class="auth">
    <h3>Login</h3>
    <div class="row">
      <div class="col-sm-4"></div>
      <div class="col-sm-4">
        <form>
          <label for="username">Username</label>
          <input type="text" class="form-control" id="username">
          <label for="password">Password</label>
          <input type="password" class="form-control" id="password">
          <button
            class="btn btn-info btn-sm"
          >Login</button>
          <button
            class="btn btn-success btn-sm"
          >Register</button>
        </form>
      </div>
      <div class="col-sm-4"></div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Auth",
  data(){

  }
}
</script>

<style scoped>

</style>