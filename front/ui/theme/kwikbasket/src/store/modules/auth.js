import axios from 'axios';
import * as types from '../mutation-types';
import router from '../../router';

export const state = {
    user: {
        firstName: '',
        lastName: ''
    },
}

export const mutations = {
    [types.SET_USER](state, user) {
        state.user = user
    },

    [types.LOGOUT](state) {
        state.user = null
    }
}

export const actions = {
    async getUserDetails({ commit }) {
        const { data } = await axios.get('index.php?path=common/home/getUserDetails');
        commit(types.SET_USER, data);
    },

    async logout({ commit }) {
        await axios.get('index.php?path=account/logout');
        commit(types.LOGOUT);
    }
}