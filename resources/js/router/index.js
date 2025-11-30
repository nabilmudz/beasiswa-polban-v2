import { createRouter, createWebHistory } from "vue-router";

import home from '../components/HomePage.vue';
import about from '../components/AboutPage.vue';
import notfound from '../components/NotFoundPage.vue';

const routes = [
    {
        path: '/',
        component: home
    },
    {
        path: '/about',
        component: about
    },
    {
        path: '/:pathMatch(.*)*',
        component: notfound
    }
]

const router = createRouter({
    history: createWebHistory(),
    linkExactActiveClass: 'active',
    routes,
})

export default router