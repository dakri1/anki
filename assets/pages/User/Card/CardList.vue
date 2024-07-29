<template>
  <div class="d-flex justify-content-center align-items-center h-100">
    <div class="card mt-5" v-if="currentCard">
      <div class="card-body p-5 rounded-3 shadow-sm bg-white">
        <h5 class="card-title text-3xl font-weight-bold mb-4">{{ currentCard.word }}</h5>
        <div v-if="showTranslation">
          <p class="card-text text-muted mb-3">Translation: {{ currentCard.translation }}</p>
          <p class="card-text text-muted">Example Sentence: {{ currentCard.sentence }}</p>
        </div>
        <div class="d-flex justify-content-center mt-4">
          <template v-if="!showTranslation">
            <button @click="showTranslation = true" class="btn btn-primary">Show Translation</button>
          </template>
          <template v-else>
            <div class="btn-group">
              <button @click="handleCorrect" class="btn btn-success">Correct</button>
              <button @click="handleHard" class="btn btn-warning">Hard</button>
              <button @click="handleFailed" class="btn btn-danger">Failed</button>
            </div>
          </template>
        </div>
      </div>
    </div>
    <div v-else>
      <p class="text-center text-muted">No cards available.</p>
    </div>
  </div>
</template>

<script>
import axios from '../../axios.js';

export default {
  data() {
    return {
      cards: [],
      currentIndex: 0,
      currentCard: null,
      folderId: this.$route.params.folderId,
      showTranslation: false
    };
  },
  async created() {
    await this.fetchCards();
    this.currentCard = this.cards[this.currentIndex] || null;
  },
  methods: {
    async fetchCards() {
      try {
        const response = await axios.get(`/folder/${this.folderId}/card/list`);
        this.cards = response.data.data;
      } catch (error) {
        console.error("Error fetching cards:", error);
      }
    },
    showNextCard() {
      if (this.currentIndex < this.cards.length) {
        this.currentCard = this.cards[this.currentIndex] || null;
        this.showTranslation = false;
      }
    },
    handleCorrect() {
      if (this.cards.length > 0) {
        this.cards.splice(this.currentIndex, 1);
      }
      this.showNextCard();
    },
    handleHard() {
      if (this.cards.length > 0) {
        const card = this.cards.splice(this.currentIndex, 1)[0];
        this.cards.splice(Math.floor(this.cards.length / 2), 0, card);
      }
      this.showNextCard();
    },
    handleFailed() {
      if (this.cards.length > 0) {
        const card = this.cards.splice(this.currentIndex, 1)[0];
        this.cards.push(card);
      }
      this.showNextCard();
    },
  },
  watch: {
    cards(newCards) {
      // Updates the currentCard if currentIndex is valid
      if (this.currentIndex < newCards.length) {
        this.currentCard = newCards[this.currentIndex];
      } else {
        // No more cards to show
        this.currentCard = null;
        
      }
    },
    currentIndex(newIndex) {
      // Updates the currentCard if currentIndex is valid
      if (newIndex < this.cards.length) {
        this.currentCard = this.cards[newIndex];
      } else {
        // No more cards to show or index out of bounds
        this.currentCard = null;
      }
    }
  }
};
</script>

<style scoped>
.card {
  width: 18rem;
}
</style>
