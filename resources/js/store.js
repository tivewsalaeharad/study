import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

export default new Vuex.Store({
    state: { 
        /* Перечень пунктов бокового меню приложения */
        sidebar: [
            { path: '/', image: "mdi-view-dashboard", caption: "Главная страница" },
            { path: '/smallgroup', image: "mdi-human-male-female", caption: "Малая группа" },
            { path: '/morning', image: "mdi-weather-sunny", caption: "Утренние звонки" },
            { path: '/callresult', image: "mdi-cellphone-basic", caption: "Результаты звонков" },
            { path: '/aims', image: "mdi-target", caption: "Личные цели" },
            { path: '/social', image: "mdi-tree", caption: "Общественный проект" },
            { path: '/national', image: "mdi-phone-classic", caption: "Национальный проект" },
            { path: '/star', image: "mdi-star", caption: "Проект \"Звезда\"" },
            { path: '/timetable', image: "mdi-calendar", caption: "Календарь событий" },
        ],
    },
    getters: {
    },
    mutations: {
    },
    actions: {
    },
})
