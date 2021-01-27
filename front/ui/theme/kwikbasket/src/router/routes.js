function page(path) {
    return () => import(/* webpackChunkName: '' */ `../pages/${path}`).then((m) => m.default || m);
}

export default [
    {
        path: '/',
        component: page('store/index.vue'),
        children: [
            { path: '', redirect: { name: 'store.storefront' } },
            { path: '/storefront',  name: 'store.storefront', component: page('store/storefront') },
        ]
    },
    { path: '*', component: page('errors/404.vue') }
]