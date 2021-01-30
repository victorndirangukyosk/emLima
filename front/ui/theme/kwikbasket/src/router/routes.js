import auth from '../middleware/auth';
import guest from '../middleware/guest';
import checkauth from '../middleware/check-auth';

function page(path) {
    return () => import(/* webpackChunkName: '' */ `../pages/${path}`).then((m) => m.default || m);
}

export default [
    {
        path: '/',
        component: page('index.vue'),
        beforeEnter: checkauth,
        children: [
            {
                path: '/',
                component: page('auth/index.vue'),
                beforeEnter: guest,
                children: [
                    { path: '', redirect: { name: 'login' } },
                    { path: '/login', name: 'login', component: page('auth/login.vue') }
                ]
            }
        ]
    },
    {
        path: '/',
        component: page('store/index.vue'),
        beforeEnter: auth,
        children: [
            { path: '', redirect: { name: 'store.storefront' } },
            { path: '/storefront',  name: 'store.storefront', component: page('store/storefront') },
        ]
    },
    {
        path: '/checkout',
        component: page('checkout/index.vue'),
        children: [
            { path: '/',  name: 'checkout', component: page('checkout/index') },
        ]
    },
    { path: '*', component: page('errors/404.vue') }
]