<template>
  <div class="container">
    <router-link class="btn btn-info mx-2" :to="{name: 'CreateFolder', params: {'levelId': this.levelId}}">Create Folder</router-link>
    <table class="table table-bordered">
      <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Folder</th>
        <th scope="col">Actions</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(folder, index) in folders" :key="folder.id">
        <td>{{ index + 1}}</td>
        <td>{{ folder.name }}</td>
        <td>
          <a @click="deleteFolder(folder.id)" class="btn btn-danger mx-2">Delete</a>
          <a class="btn btn-info mx-2">Edit</a>
          <router-link class="btn btn-info mx-2" :to="{name: 'CardList', params: {'folderId': folder.id}}">Manage</router-link>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from '../axios.js';

export default {
  name: 'FolderList',

  data() {
    return {
      levelId: this.$route.params.levelId,
      folders: []
    };
  },
  mounted() {
    this.fetchFolders(this.levelId);
  },
  methods: {
    fetchFolders(levelId) {
      axios.get(`/level/${levelId}/folder/list`)
          .then(response => {
            this.folders = response.data.data;
          })
          .catch(error => {
            console.error('Error fetching languages:', error);
          });
    },

    deleteFolder(folderId) {
      axios.delete(`/folder/${folderId}/delete`)
          .then(() => {
            this.folders = this.folders.filter(folder => folder.id !== folderId);
          })
          .catch(error => {
            console.log(error.response.data.error);
          })
    }
  }
};
</script>
