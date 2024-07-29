<template>
  <div class="container">
    <router-link class="btn btn-info" :to="{name: 'CreateCard', params: {folderId: this.folderId}}">Create Card</router-link>
    <table class="table table-bordered">
      <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Word</th>
        <th scope="col">Translation</th>
        <th scope="col">Sentence</th>
        <th scope="col">Actions</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(card, index) in cards" :key="card.id">
        <td>{{ index + 1 }}</td>
        <td>{{ card.word }}</td>
        <td>{{ card.translation }}</td>
        <td>{{ card.sentence }}</td>
<!--        <td>{{ card.sentence }}</td>-->
        <td>
          <a @click="deleteCard(card.id)" class="btn btn-danger mx-2">Delete</a>
          <a class="btn btn-info mx-2">Edit</a>
          <!--          <router-link :to="'/language/' + language.id + '/level/list'">Manage</router-link>-->
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from '../axios.js';
export default {
  name: 'CardList',

  data() {
    return {
      cards: [],
      folderId: this.$route.params.folderId,
    };
  },
  mounted() {
    this.fetchCards(this.folderId);
  },
  methods: {
    fetchCards(folderId) {
      axios.get(`/folder/${this.folderId}/card/list`)
          .then(response => {
            this.cards = response.data.data;
          })
          .catch(error => {
            console.error('Error fetching languages:', error);
          });
    },
    deleteCard(cardId) {
      axios.delete(`/card/${cardId}/delete`)
          .then(() => {
            this.cards = this.cards.filter(card => card.id !== cardId);
          })
          .catch(error => {
            console.log(error.response.data.error);
          })
    }
  }
};
</script>
