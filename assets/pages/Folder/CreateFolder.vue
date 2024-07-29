

<script>
import axios from 'axios';
import { useRouter, useRoute } from 'vue-router';
import {ref} from "vue";

export default {
  setup() {
    const router = useRouter();
    const route = useRoute();
    const levelId = route.params.levelId;
    const name = ref(''); // For the input field

    const formData = new FormData(); // Create FormData here

    const handleImageChange = (e) => {
      formData.append('image', e.target.files[0]); // Append image to FormData
    };

    const createFolder = () => {
      formData.append('name', name.value); // Add name to FormData after user input

      axios.post(`http://127.0.0.1:8000/api/v1/level/${levelId}/folder/create`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
          .then(response => {
            router.push(`/level/${levelId}/folder/list`);
          })
          .catch(error => {
            console.log(error);
          });
    };

    return {
      name,
      handleImageChange,
      createFolder,
    };
  }
};
</script>


<template>
  <div class="container mt-3">
    <h1>Create Folder</h1>
    <form @submit.prevent="createFolder">
      <div class="my-2">
        <label for="folder"> Folder: </label>
        <input type="text" v-model="name" name="name" class="form-control" id="uname" placeholder="Enter folder name">
      </div>
      <div class="my-2">
        <label for="image"> Image: </label>
        <input type="file" @change="handleImageChange" name="image" class="form-control" id="image" placeholder="Choose image">
      </div>
      <button type="submit" class="btn btn-danger">Create</button>
    </form>
  </div>

</template>

<style scoped>

</style>