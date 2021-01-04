<template>
  <div>
    <button class="new-note-btn" @click="addNote">New Note</button>
    <router-link to="/add-notes" class="new-note-btn">Add Notes</router-link>
  </div>
</template>

<script>
import router from "@/router";
export default {
  name: "AddButtonNew",
  methods: {
    addNote(){
      this.$emit('addNote');
    }
  }
}
</script>

<style lang="scss" scoped>
.new-note-btn {
  width: 200px;
  margin: 80px 10px 20px;
  background-color: #FFF;
  padding: 10px 32px;
  border: 1px solid #e0e0e0;
  font-size: 26px;
  outline: 0;
  transition: all 0.3s;
  cursor: pointer;
  font-family: 'Caveat', cursive;
  text-decoration: none;
  color: #42b983;

&:hover {
   box-shadow: 0 5px 7px rgba(0, 0, 0, 0.1);
 }

&:active {
   position: relative;
   top: 1px;
 }
}
</style>