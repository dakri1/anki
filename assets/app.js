import './scss/app.scss'
import {compile, createApp} from "vue";
import App from "./App.vue";
import router from "./routes.js";

const app = createApp(App).use(router);
app.mount('#app');