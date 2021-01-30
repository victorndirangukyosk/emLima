import store from '../store';

export default async (to, from, next) => {
    if (store.state.auth.user != null && store.state.auth.token) {
        try {
            await store.dispatch('auth/getUserDetails');
        } catch (e) {}
    }

    next();
};