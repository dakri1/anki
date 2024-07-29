<template>
  <div class="container">
    <router-link class="btn btn-success" to="/language/create">Create Language</router-link>
    <table class="table table-bordered">
      <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Language</th>
        <th scope="col">Actions</th>
      </tr>
      </thead>
      <tbody>
        <tr v-for="(language, index) in languages" :key="language.id">
          <td>{{ index + 1}}</td>
          <td>{{ language.name }}</td>
          <td>
            <a @click="deleteLanguage(language.id)" class="btn btn-danger mx-2">Delete</a>
            <a class="btn btn-info mx-2">Edit</a>
            <router-link class="btn btn-info" :to="'/language/' + language.id + '/level/list'">Manage</router-link>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from '../axios.js';

export default {
  name: 'ListLanguage',

  data() {
    return {
      languages: []
    };
  },
  mounted() {
    this.fetchLanguages();
  },
  methods: {
    fetchLanguages() {
      axios.get('/language/list')
          .then(response => {
            this.languages = response.data.data;
          })
          .catch(error => {
            console.error('Error fetching languages:', error);
          });
    },

    deleteLanguage(languageId) {
      axios.delete(`/language/${languageId}`)
          .then(() => {
            this.languages = this.languages.filter(language => language.id !== languageId);
          })
          .catch(error => {
            console.log(error.response.data.error);
          })
    }
  }
};
</script>
