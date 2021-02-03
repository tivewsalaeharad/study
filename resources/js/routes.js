import Dashboard from './components/Dashboard.vue';
import Clinics from './components/Clinics.vue';
import Financing from './components/Financing.vue';

/* Перечень маршрутов SPA-приложения */
const routes = [
    { path: '/', component: Dashboard },
    { path: '/clinics', component: Clinics },
    { path: '/financing', component: Financing },
];

export default routes;
