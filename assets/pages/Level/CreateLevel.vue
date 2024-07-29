<script>
import axios from '../axios.js';
import {useRouter} from "vue-router";
import {ref} from "vue";

export default {
  name: 'CreateLanguage',
  setup() {
    const router = useRouter(); // Call useRouter inside setup
    const languages = ref([])

    axios.get('/language/list')
        .then(response => {
          languages.value = response.data.data;
        })
        .catch(error => {
          console.error('Error fetching languages:', error);
        });

    return {
      formData: {
        name: '',
        languageId: '',
      },
      languages,

      createLevel() {
        axios.post(`/language/${this.formData.languageId}/level/create`, this.formData)
            .then(response => {
              router.push(`/language/${this.formData.languageId}/level/list`);
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
    <form @submit.prevent="createLevel">
      <div class="my-2">
        <label for="Level"> Level: </label>
        <input type="text" v-model="formData.name" class="form-control" id="uname" placeholder="Enter level name">
      </div>
      <div class="my-2">
        <label for="Language">Language: </label>
        <select v-model="formData.languageId" class="form-control">
          <option value="">Select a language</option>
          <option v-for="language in languages" :key="language.id" :value="language.id">{{ language.name }}</option>
        </select>
      </div>

      <button type="submit" class="btn btn-danger">Create</button>
    </form>
  </div>

</template>

<style scoped>

</style>