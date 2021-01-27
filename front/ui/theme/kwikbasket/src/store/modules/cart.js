import axios from 'axios';
import * as types from '../mutation-types';

export const state = {
    cartProducts: []
}

export const getters = {
    itemsInCart: state => state.cartProducts.length,

    basketCost: state => {
        return state.cartProducts.reduce(function (prev, { quantity, price }) {
            return prev + (currency(price).value * quantity);
        }, 0);
    }
}

export const mutations = {
    [types.ADD_PRODUCT_TO_CART](state, product) {
        state.cartProducts.push(product);
    },

    [types.UPDATE_PRODUCT_QUANTITY](state, { key, newQuantity }) {
        const index = state.cartProducts.map(item => item.key).indexOf(key);
        state.cartProducts[index].quantity = newQuantity;
    },

    [types.REMOVE_PRODUCT_FROM_CART](state, product) {
        const index = state.cartProducts.map(item => item.key).indexOf(product.key);
        state.cartProducts.splice(index, 1);
    }
}

export const actions = {
    addProductToCart({ commit }, product) {
        commit(types.ADD_PRODUCT_TO_CART, product);
        const { product_id, variation_id, store_id, quantity, product_notes, produce_type } = product;
        axios.post('index.php?path=checkout/cart/add', {
            product_id, variation_id, store_id, quantity, product_notes, produce_type
        });
    },

    removeProductFromCart({ commit }, product) {
        commit(types.REMOVE_PRODUCT_FROM_CART, product);

        axios.post('index.php?path=checkout/cart/remove', {
            key: product.key
        });
    },

    updateProductQuantity({ commit }, { key, quantity }) {
        commit(types.UPDATE_PRODUCT_QUANTITY, { key, quantity });

        // TODO: Refactor
        const product_note = "";
        const produce_type = "";

        axios.post('index.php?path=checkout/cart/update', {
            key, quantity, product_note, produce_type
        });
    }
}