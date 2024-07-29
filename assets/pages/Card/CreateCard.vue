<script>
import axios from '../axios.js';
import {useRoute, useRouter} from "vue-router";

export default {
  name: 'CreateCard',
  setup() {
    const router = useRouter(); // Call useRouter inside setup
    const route = useRoute();
    const folderId = route.params.folderId;
    return {
      formData: {
        word: '',
        translation: '',
        sentence: '',
      },
      createCard() {
        axios.post(`/folder/${folderId}/card/create`, this.formData)
            .then(response => {
              router.push(`/folder/${folderId}/card/list`);
            })
            .catch(error => {
              console.log(error);
            });
      }
    }
  },
}
</script>

<template>
  <div class="container mt-3">
    <h1>Create Card</h1>
    <form @submit.prevent="createCard">
      <div class="my-2">
        <label for="Word"> Word: </label>
        <input type="text" v-model="formData.word" class="form-control" id="uname" placeholder="Enter word">
      </div>
      <div class="my-2">
        <label for="Translation"> Translation: </label>
        <input type="text" v-model="formData.translation" class="form-control" id="uname" placeholder="Enter translation">
      </div>
      <div class="my-2">
        <label for="Sentence"> Sentence: </label>
        <input type="text" v-model="formData.sentence" class="form-control" id="uname" placeholder="Enter sentence">
      </div>
      <button type="submit" class="btn btn-danger">Create</button>
    </form>
  </div>

</template>

<style scoped>

</style>