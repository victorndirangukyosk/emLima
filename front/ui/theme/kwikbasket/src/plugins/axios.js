import axios from 'axios';
import iziToast from 'izitoast';


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