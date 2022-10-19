import axios from 'axios';
import storage from '../storage';

const state = {
    users: [],
    user: null,
    userNotificationErrorCreate: {submit: false},
    token: null,
};

const getters = {
    usersList: state => state.users,
    user: state => state.user,
    token: state => state.token,
    userNotificationErrorCreate: state => state.userNotificationErrorCreate,
};

const actions = {
    async fetchUsers({commit}){
        const response = await axios.get("http://localhost:3000/users");
        commit("setUsers", response.data);
    },
    async login({commit}, user){
        const response = await axios.post(route('auth.login'), user);
        commit("setToken", response.data);
    },
    async getToken({commit}){
        commit('getToken');
    },
    async addUser({commit}, user){
        commit("setUserNotificationErrorCreate", {submit: false});
        axios.post(route("auth.register"), user).then((response) => {
            commit("setUserNotificationErrorCreate", {submit: true, response: response.data})
        }).catch((error) => {
            console.log(error.response.data);
            commit("setUserNotificationErrorCreate", {submit: true, response: error.response.data});
        });

        //commit("addNewUser", response.data);
    },
    async deleteUser({commit}, id){
        await axios.delete(`http://localhost:3000/users/${id}`);
        commit("removeUser", id)
    },

};

const mutations = {
    setToken: (state, data) => {
        state.token = {token: data.data, expires_at: data.expires_at};
        storage.set('token', {token: data.data, expires_at: data.expires_at});
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
