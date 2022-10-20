import axios from 'axios';
import storage from '../storage';

const state = {
    users: [],
    user: null,
    userNotificationErrorCreate: {submit: false},
    token: null,
    userNotificationLogin: {submit: false},
};

const getters = {
    usersList: state => state.users,
    user: state => state.user,
    token: state => state.token,
    userNotificationErrorCreate: state => state.userNotificationErrorCreate,
    userNotificationLogin: state => state.userNotificationLogin,
};

const actions = {
    async login({commit}, user){
        commit("setUserNotificationLogin", {submit: false});
        axios.post(route('auth.login'), user).then((response) => {
            commit("setToken", response.data);
            commit("setUserNotificationLogin",{submit: true, response: response.data});
        }).catch((error) => {
            commit("setUserNotificationLogin",{submit: true, response: error.response.data});
        });

    },
    async getToken({commit}){
        commit('getToken');
    },
    async addUser({commit}, user){
        commit("setUserNotificationErrorCreate", {submit: false});
        axios.post(route("auth.register"), user).then((response) => {
            commit("setUserNotificationErrorCreate", {submit: true, response: response.data})
        }).catch((error) => {
            commit("setUserNotificationErrorCreate", {submit: true, response: error.response.data});
        });
    },
    async closeSession({commit}){

        commit('deleteToken');
    }
};

const mutations = {
    setToken: (state, data) => {
        state.token = {token: data.data, expires_at: data.expires_at};
        storage.set('token', {token: data.data, expires_at: data.expires_at});
    },

    setUserNotificationLogin: (state, status) => {
        state.userNotificationLogin = status;
    },

    deleteToken(state){
        storage.remove('token');
        state.token = null;
    },

    getToken: (state) => {
        state.token = storage.get('token') ?? null;
    },

    setUserNotificationErrorCreate: (state, status) => {
        state.userNotificationErrorCreate = status;
    },

    setUser: (state, user) => (
        state.user = user
    ),

    setUsers: (state, users) => (
        state.users = users
    ),
    addNewUser: (state, user) => state.users.unshift(user),
    removeUser: (state, id) => (
        state.users.filter(user => user.id !== id),
            state.users.splice(user => user.id, 1)
    )
};
export default {
    state,
    getters,
    actions,
    mutations,
}
