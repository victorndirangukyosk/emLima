import axios from 'axios';
import * as types from '../mutation-types';
import _ from 'lodash';

export const state = {
  searchQuery: '',
  isSearchActive: false,
  apiSearchResults: [],
  productCategories: [],
}

export const getters = {
  searchResults: state => {
    const results = new Set();
    return state.productCategories
      .flatMap(category => category.products)
      .concat(state.apiSearchResults)
      .filter(product => product.name.toLowerCase().indexOf(state.searchQuery.toLowerCase()) >= 0)
      .filter(product => {
        const key = product.product_id;
        return results.has(key) ? false : results.add(key);
      });
  },
}

export const mutations = {
  [types.SET_PRODUCT_CATEGORIES](state, categories) {
    state.productCategories = categories
  },

  [types.SET_SEARCH_RESULTS](state, products) {
    products.forEach((product) => {
      const index = state.apiSearchResults.findIndex(result => result.name == product.name);
      if (index === -1) state.apiSearchResults.push(product);
    });
  }
}

export const actions = {
  async getCategoriesWithProducts({ commit }) {
    const { data } = await axios.get('index.php?path=common/home/getCategoriesWithProducts');
    commit(types.SET_PRODUCT_CATEGORIES, data);
  },

  searchProducts:
    _.debounce(async function ({ commit, state }) {
      if(state.searchQuery.length > 0 && state.searchQuery.trim() != "") {
        state.isSearchActive = true;
        const { data } = await axios.get(`index.php?path=product/search/product_search&filter_name=${encodeURIComponent(state.searchQuery)}`);
        commit(types.SET_SEARCH_RESULTS, data);
        state.isSearchActive = false; 
      }
    }, 500),
}