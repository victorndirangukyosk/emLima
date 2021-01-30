import axios from 'axios';
import * as types from '../mutation-types';
import router from '../../router';

export const state = {
    user: {
        "customer_id": "243",
        "customer_group_id": "7",
        "store_id": "0",
        "firstname": "Florence",
        "lastname": "Oloo",
        "email": "mailroshani27@gmail.com",
        "telephone": "722523559",
        "fax": "",
        "password": "fe154618e1d9ef27948de5472de46a6ad04d1c75",
        "salt": "27b7fadec",
        "cart": "a:0:{}",
        "wishlist": "",
        "newsletter": "0",
        "address_id": "461",
        "custom_field": "a:0:{}",
        "ip": "127.0.0.1",
        "status": "1",
        "approved": "1",
        "safe": "1",
        "token": "",
        "member_upto": "0000-00-00",
        "date_added": "2020-04-27 21:57:10",
        "dob": "30/11/-0001",
        "gender": "female",
        "refree_user_id": null,
        "device_id": "dwQkyws1Tfs:APA91bFphSZo7pZFt-oAmf78aqMVuvuX8lxfIbx_wktkPoYRzL0KZz5aO4UWwUCcs95RCVrA-77yrmJuO3bcdjW9DDT4qNVYWhgJGX1ul-6JCrhONbEoR7ElBdn_JtRtDvm4W0pj9GBu",
        "company_name": "Roshani Residency",
        "company_address": "Jabavu Road, Hurlingham, Nairobi",
        "customer_category": null,
        "tempPassword": null,
        "parent": null,
        "account_manager_id": null,
        "order_approval_access": "0",
        "order_approval_access_role": null,
        "sms_notification": "1",
        "mobile_notification": "1",
        "email_notification": "1",
        "source": null,
        "latitude": null,
        "longitude": null,
        "sub_customer_order_approval": "1",
        "user_rewards_available": 10,
        "stripe_details": false
    },
    token: localStorage.getItem('token')
}

export const mutations = {
    [types.SET_TOKEN](state, token) {
        state.token = token;
        localStorage.setItem('token', token);
    },

    [types.SET_USER](state, user) {
        state.user = user;
    },

    [types.LOGOUT](state) {
        state.user = null;
        state.token = null;
        localStorage.removeItem('token');
    }
}

export const actions = {
    async login({ commit, dispatch }, { username, password }) {
        const { data } = await axios({
            method: 'POST',
            headers: { 'content-type': 'application/x-www-form-urlencoded' },
            url: 'index.php?path=api/customer/login',
            data: qs.stringify({ username, password })
        });
        commit(types.SET_TOKEN, data.token);
        await dispatch('getUserDetails');
        // route to storefront
    },

    async getUserDetails({ commit }) {
        const { data } = await axios.get('api/customer/account/userdetails');
        commit(types.SET_USER, data.data);
    },

    async logout({ commit }) {
        await axios.get('index.php?path=account/logout');
        commit(types.LOGOUT);
    }
}