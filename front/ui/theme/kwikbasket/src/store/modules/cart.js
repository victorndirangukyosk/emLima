import axios from 'axios';
import * as types from '../mutation-types';
import currency from "../../util/currency";
import qs from 'qs';

export const state = {
    // Product being displayed on popup
    selectedProduct: {},
    
    cartProducts: []
}

export const getters = {
    itemsInBasket: state => state.cartProducts.length,

    basketCost: state => {
        return state.cartProducts.reduce(function (prev, { quantity, price }) {
            return prev + (currency(price).value * quantity);
        }, 0);
    },
}

export const mutations = {
    [types.ADD_PRODUCT_TO_CART](state, product) {
        state.cartProducts.push(product);
    },

    [types.UPDATE_PRODUCT_QUANTITY](state, { key, quantity }) {
        const index = state.cartProducts.map(item => item.key).indexOf(key);
        state.cartProducts[index].quantity = quantity;
    },

    [types.REMOVE_PRODUCT_FROM_CART](state, product) {
        const index = state.cartProducts.map(item => item.key).indexOf(product.key);
        state.cartProducts.splice(index, 1);
    },

    [types.SET_PRODUCTS_IN_CART](state, products) {
        state.cartProducts = products;
    },
}

export const actions = {
    addProductToCart({ commit }, product) {
        commit(types.ADD_PRODUCT_TO_CART, product);

        const { product_id, variation_id, store_id, quantity, productNotes: product_notes, produceType: produce_type } = product;                
        axios({
            method: 'POST',
            headers: { 'content-type': 'application/x-www-form-urlencoded' },
            url: 'index.php?path=checkout/cart/add',
            data: qs.stringify({ product_id, variation_id, store_id, quantity, product_notes, produce_type })
        });
    },

    removeProductFromCart({ commit }, product) {
        commit(types.REMOVE_PRODUCT_FROM_CART, product);

        axios({
            method: 'POST',
            headers: { 'content-type': 'application/x-www-form-urlencoded' },
            url: 'index.php?path=checkout/cart/remove',
            data: qs.stringify({ key: product.key })
        });
    },

    updateProductQuantity({ commit }, { key, quantity }) {
        commit(types.UPDATE_PRODUCT_QUANTITY, { key, quantity });

        // TODO: Refactor
        const product_note = "";
        const produce_type = "";   

        axios({
            method: 'POST',
            headers: { 'content-type': 'application/x-www-form-urlencoded' },
            url: 'index.php?path=checkout/cart/update',
            data: qs.stringify({ key, quantity, product_note, produce_type })
        });
    },

    async getProductsInCart({ commit }) {
        const { data } = await axios.get('index.php?path=common/cart/getProductsInCart');
        commit(types.SET_PRODUCTS_IN_CART, data);
    }
}