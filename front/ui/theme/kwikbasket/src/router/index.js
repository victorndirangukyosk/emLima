import Vue from 'vue';
import store from '../store';
import routes from './routes';
import Router from 'vue-router';
import { sync } from 'vuex-router-sync';
import NProgress from 'nprogress';

Vue.use(Router);

const router = new Router({
    mode: 'history',
    linkActiveClass: 'open',
    routes,
    scrollBehavior(to, from, savedPosition) {
        return { x: 0, y: 0 };
    },
});

sync(store, router);

router.beforeEach((to, from, next) => {
    if (to.path) {
        NProgress.start();
        NProgress.set(0.1);
    }
    next();
});

router.afterEach(() => {
    const preloader = document.getElementById('loading_wrap');
    if (preloader) {
        preloader.style.display = 'none';
    }
    setTimeout(() => NProgress.done(), 500);
});

export default router;
