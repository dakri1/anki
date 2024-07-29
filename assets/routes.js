import Home from "./pages/User/Home/Home.vue";
import Login from "./pages/Auth/Login.vue";
import CreateLanguage from "./pages/Language/CreateLanguage.vue";
import ListLanguage from "./pages/Language/ListLanguage.vue";
import CreateLevel from "./pages/Level/CreateLevel.vue";
import LevelList from "./pages/Level/LevelList.vue";
import FolderList from "./pages/Folder/FolderList.vue";
import CreateFolder from "./pages/Folder/CreateFolder.vue";
import CreateCard from "./pages/Card/CreateCard.vue";
import CardList from "./pages/Card/CardList.vue";
import UserCardList from "./pages/User/Card/CardList.vue"
import FolderView from "./pages/User/Folder/FolderView.vue"
import {createRouter, createWebHistory} from "vue-router";

const router = createRouter({
    history: createWebHistory(),

    routes: [
        { path: '/',            component: Home, name: 'HomePage' },
        { path: '/login',        component: Login },
        { path: '/level/:levelId/card/list', component: CardList, name: 'CardList'},
        { path: '/language/create', component: CreateLanguage},
        { path: '/language/list',   component: ListLanguage},
        { path: '/language/:languageId/level/create', component: CreateLevel},
        { path: '/language/:languageId/level/list', component: LevelList},
        { path: '/level/:levelId/folder/list', component: FolderList, name: 'FolderList'},
        { path: '/level/:levelId/folder/create', component: CreateFolder, name: 'CreateFolder'},
        { path: '/folder/:folderId/card/list', component: CardList, name: 'CardList'},
        { path: '/folder/:folderId/card/create', component: CreateCard, name: 'CreateCard'},

        // User
        { path: '/user/folder/:folderId/card/list', component: UserCardList, name: 'UserCardList'},
        { path: '/user/folder/list', component: FolderView, name: 'FolderView'},


    ]
})

export default router;