<script>
import axios from '../../axios.js';

import CompleteBadge from "../../../components/badge/CompleteBadge.vue";
import IncompleteBadge from "../../../components/badge/IncompleteBadge.vue";
import UnfinishedBadge from "../../../components/badge/UnfinishedBadge.vue";

export default {
  name: 'FolderView',
  components: {IncompleteBadge, CompleteBadge, UnfinishedBadge},

  data() {
    return {
      folders: [],
      imagePath: '/images/image.jpg',
      levels: [],
      selectedLevel: null, // Default level will be set after fetching levels
      languageId: 1
    }
  },

  async created() {
    await this.fetchLevels(this.languageId);
    if (this.levels.length > 0) {
      this.selectedLevel = this.levels[0].name; // Set default level to the first one in levels
      await this.fetchFolders(this.levels[0].id); // Fetch folders based on the default level id
    }
  },

  methods: {
    async fetchFolders(levelId) {
      try {
        const response = await axios.get(`/level/${levelId}/folder/user/list`);
        this.folders = response.data.data;
      } catch (error) {
        console.error("Error fetching cards:", error);
      }
    },

    async fetchLevels(languageId) {
      try {
        const response = await axios.get(`/language/${languageId}/level/list`);
        this.levels = response.data.data;
      } catch (error) {
        console.log("Error fetching levels", error);
      }
    },

    async levelSelected(level) {
      this.selectedLevel = level.name;
      this.folders = []; // Очищаем список колод перед загрузкой новых
      await this.fetchFolders(level.id); // Fetch folders based on the selected level id
    }
  }
}

</script>

<template>
  <header class="bg-light py-5 mb-5 shadow-sm">
    <div class="container">
      <h1 class="display-2 text-primary font-weight-bold">English Learning</h1>
      <p class="lead text-secondary">Choose a deck to start learning:</p>

      <div class="btn-group" role="group" aria-label="Language Level">
        <button type="button" class="btn btn-outline-secondary" v-for="level in levels" :key="level.id"
                :class="{ active: selectedLevel === level.name }"
                @click="levelSelected(level)">
          {{ level.name }}
        </button>
      </div>
    </div>
  </header>

  <div class="container">
    <div id="decks-container" class="row">
      <div v-for="(folder, index) in folders" :key="index" class="col-md-4 mb-4">
        <div class="card" style="width: 18rem;">
          <img :src="folder.image" class="card-img-top" alt="...">
          <div class="card-body">
            <h5 class="card-title">{{ folder.name }}
              <UnfinishedBadge v-if="folder.status === 'unfinished'"></UnfinishedBadge>
              <CompleteBadge v-if="folder.status === 'solved'"></CompleteBadge>
              <IncompleteBadge v-if="folder.status === 'incomplete'"></IncompleteBadge>
<!--              <span class="badge bg-danger text-white font-weight-bold">{{ folder.status }}</span>-->
            </h5>
            <p class="card-text"><strong>{{ folder.level }}</strong></p>
            <router-link class="btn btn-primary" :to="{ name: 'UserCardList', params: {'folderId': folder.id}}">Choose Deck</router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.card {
  border-radius: 10px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

header {
  border-radius: 8px;
}

h1.display-2 {
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
}

.lead {
  font-size: 1.5rem;
}
</style>