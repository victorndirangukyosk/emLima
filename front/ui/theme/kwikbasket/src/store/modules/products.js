import axios from 'axios';
import * as types from '../mutation-types';

export const state = {
    productCategories: [],
}

export const getters = {}

export const mutations = {
  [types.SET_PRODUCT_CATEGORIES] (state, categories) {
    state.productCategories = categories
  },
}

export const actions = {
    async getCategoriesWithProducts({ commit }) {
        const { data } = await axios.get('index.php?path=common/home/getCategoriesWithProducts');
        commit(types.SET_PRODUCT_CATEGORIES, data);
    }
}