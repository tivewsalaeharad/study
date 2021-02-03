import Vue from 'vue'
import VueRouter from 'vue-router';
import routes from './routes';
import store from './store';
import SampleComponent from './components/auth/sample'

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes
});

Vue.component('register', SampleComponent);

window.app = new Vue({
    el: "#app",
    router,
    store
})
