<template>
  <div class="tc-notes-wrapper">
    <add-button-new @addNote="addNote" />
    <div class="tc-notes">
      <note v-for="(note, index) in notes" :key="index" :note="note" @deleteNote="deleteNote" @noteUpdated="noteUpdated" />
    </div>
  </div>
</template>

<script>
  import AddButtonNew from "./AddButtonNew";
  import Note from './Note';
  import axios from 'axios';
  import notesService from "@/service/notes_service";
  import httpClient from "@/service/http.service";

export default {
  name: "Notes",
  components: {Note, AddButtonNew},
  async data() {
    return {
      notes: []
    }
  },
  methods: {
    async addNote(){
      const {status,data} = await httpClient.post('note', {});
      if(status === 201){
        this.notes.unshift(data);
      }
    },
    async deleteNote(note){
      const {status,data} = await httpClient.delete(`note/${note.id}`, {});
      if(status === 204){
        this.notes.splice(this.notes.indexOf(note), 1);
      }
    },
    async noteUpdated(note){
      const {status,data} = await httpClient.put(`note/${note.id}`, {});
      if(status === 200){
        this.notes.unshift(data);
      }
    }
  },
  async mounted(){
    const {status,data} = await httpClient.get('note');
    if(status === 200){
      this.notes = data;
    }
  }
}
</script>

<style lang="scss" scoped>
.tc-notes-wrapper {
.tc-notes {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  margin: 0 auto;

}
}
</style>