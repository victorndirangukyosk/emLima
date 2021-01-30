import store from '../store';

export default (to, from, next) => {
    if (store.state.auth.user != null) {
        next({ name: 'store.storefront' });
    } else {
        next();
    }
};