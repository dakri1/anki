<script>
import axios from '../axios.js';
import {useRouter} from "vue-router";

export default {
  name: 'CreateLanguage',
  setup() {
    const router = useRouter(); // Call useRouter inside setup

    return {
      formData: {
        name: '',
      },
      createLanguage() {
        axios.post('/language/create', this.formData)
            .then(response => {
              router.push('/language/list');
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
    <h1>Create Language</h1>
    <form @submit.prevent="createLanguage">
      <div class="my-2">
        <label for="language"> Language: </label>
        <input type="text" v-model="formData.name" class="form-control" id="uname" placeholder="Enter language name">
      </div>
      <button type="submit" class="btn btn-danger">Submit</button>
    </form>
  </div>

</template>

<style scoped>

</style>