<template>
  <div class="form-wrapper">
    <h1>User Create</h1>
    <form action="" @submit.prevent="login">
      <div v-if="errors" class="errors">
        <p v-for="(error, field) in errors" :key="field">
          {{error[0]}}
        </p>
      </div>
      <input type="text" v-model="form.username" placeholder="Username"><br>
      <input type="password" v-model="form.password" placeholder="Password"><br>
      <input type="password" v-model="form.password_repeat" placeholder="Password Repeat"><br>
      <button class="btn" @click.prevent="saved">Save</button>
      <router-link to="/auth/login">Qaytish</router-link>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default{
  name: 'Registration',
  data(){
    return {
      form: {
        username: '',
        password: '',
        password_repeat: ''
      },
      errors: false
    }
  },
  methods: {
    async saved(){
      try{
        const success = await axios.post('http://localhost:8080/api/users/signup', this.form);
        if(success){
          alert("Saqlandi");
        }
        else{
          alert("Saqlanmadi");
        }
      }catch (e){
        this.errors = e.response.data.errors
        console.log('false');
      }

    }
  }
}
</script>

<style lang="scss">
.btn{
  padding: 10px 25px;
  border: none;
  position: relative;
  left: -15px;
  background-color: #42b983;
  cursor: pointer;
}
.form-wrapper{
  background: white;
  padding: 20px;
  width: 300px;
  margin: 0 auto;
  border-radius: 4px;
  box-shadow: 3px 3px 10px rgba(0,0,0,0.2);

  h1{
    text-align: center;
    margin-bottom: 30px;
  }
}
form{
  input{
    display: block;
    width: 100%;
    border: 2px solid #e6e6e6;
    padding: 6px 12px;
    transition: all 0.3s;

    &:hover{
      background-color: darken(#4fb2c1, 10%);
    }
  }
  .errors{
    margin-bottom: 15px;
    padding: 10px 15px;
    color: #fff;
    background-color: #ff6969;
    font-size: 12px;

    p{
      margin: 0px;
    }
  }
  .link{
    font-size: 80%;
    float: right;
  }
}
</style>
