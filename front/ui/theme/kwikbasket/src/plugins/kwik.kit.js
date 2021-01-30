import BootstrapVue from 'bootstrap-vue';
import Vuelidate from 'vuelidate';
import VueLazyload from 'vue-lazyload';
import VueGoodTable from 'vue-good-table';
import VueTagsInput from '@johmun/vue-tags-input';
import VueSweetalert2 from 'vue-sweetalert2';
import Meta from 'vue-meta';
import V2Datepicker from 'v2-datepicker';

import '@/assets/styles/scss/kwikbasket.scss';
import 'izitoast/dist/css/iziToast.min.css';
import 'v2-datepicker/lib/index.css';

export default {
    install(Vue) {
        Vue.component('store-layout', () => import('../layouts/'));

        Vue.component('vue-perfect-scrollbar', () => import('vue-perfect-scrollbar'));

        Vue.use(BootstrapVue);

        Vue.use(VueGoodTable);

        Vue.use(VueSweetalert2);

        Vue.use(V2Datepicker)

        Vue.use(VueTagsInput);

        Vue.use(Vuelidate);

        Vue.use(Meta, {
            keyName: 'metaInfo',
            attribute: 'data-vue-meta',
            ssrAttribute: 'data-vue-meta-server-rendered',
            tagIDKeyName: 'vmid',
            refreshOnceOnNavigation: true,
        });

        Vue.use(VueLazyload, {
            observer: true,
            observerOptions: {
                rootMargin: '0px',
                threshold: 0.1,
            },
        });
    },
};