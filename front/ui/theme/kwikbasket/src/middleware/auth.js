import store from '../store';

export default async (to, from, next) => {
    if (store.state.auth.user == null) {
        next({ name: 'login' });
    } else {
        next();
    }
};