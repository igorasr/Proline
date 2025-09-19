import { createRouter, createWebHistory } from "vue-router";
import EnvioPage from "./Pages/EnvioPage.vue";
import StatusPage from "./Pages/StatusPage.vue";

const routes = [
    { path: '/app/envio',  name: 'envio',  component: EnvioPage },
    { path: '/app/status', name: 'status', component: StatusPage },
];

export default createRouter({
    history: createWebHistory(),
    routes,
});