<template>
  <div class="container">
    <router-link class="btn btn-info" :to="'/language/' + $route.params.languageId + '/level/create'">Create Level
    </router-link>
    <table class="table table-bordered">
      <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Level</th>
        <th scope="col">Actions</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(level, index) in levels" :key="level.id">
        <td>{{ level.id }}</td>
        <td>{{ level.name }}</td>
        <td>
          <a @click="deleteLevel(level.id)" class="btn btn-danger mx-2">Delete</a>
          <a class="btn btn-info mx-2">Edit</a>
          <router-link class="btn btn-info mx-2" :to="{name: 'FolderList', params: {'levelId': level.id}}">Manage</router-link>

        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from '../axios.js';

export default {
  name: 'LevelList',

  data() {
    return {
      levels: []
    };
  },
  mounted() {
    const languageId = this.$route.params.languageId;
    this.fetchLanguages(languageId);
  },
  methods: {
    fetchLanguages(languageId) {
      axios.get(`/language/${languageId}/level/list`)
          .then(response => {
            this.levels = response.data.data;
          })
          .catch(error => {
            console.error('Error fetching languages:', error);
          });
    },
    deleteLevel(levelId) {
      axios.delete(`/level/${levelId}`)
          .then(() => {
            this.levels = this.levels.filter(level => level.id !== levelId);
          })
          .catch(error => {
            console.log(error);
          })
    }
  }
};
</script>
