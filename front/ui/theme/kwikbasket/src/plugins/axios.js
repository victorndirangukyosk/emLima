import axios from 'axios';
import store from '../store';
import iziToast from 'izitoast';

// Request interceptor
axios.interceptors.request.use((request) => {
    const token = store.state.auth.token;
    if (token) {
        request.headers.common['Authorization'] = `Bearer ${token}`;
        request.headers.common['X-User'] = 'customer';
    }
    return request;
});

axios.interceptors.response.use(
    (response) => response,
    (error) => {
        const { status } = error.response;

        if (status >= 500) {
            iziToast.error({
                title: 'Oops',
                message: "An error occurred, it's our fault",
                position: 'topRight',
            });
        }

        return Promise.reject(error);
    }
);