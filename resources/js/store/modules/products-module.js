import axios from 'axios';
import storage from '../storage';

const state = {
    productList: [],
};

const getters = {
    productList: state => state.productList,
};

const actions = {
    async fetchProducts({commit, rootState}){
        let config = {
            headers: {
                Authorization: "Bearer " + rootState.UsersModule.token.token,
            }
        };

        const response = await axios.get(route("api.product.get"),  config);
        commit("setProducts", response.data.data);
    }

};

const mutations = {
    setProducts: (state, productList) => (
        state.productList = productList
    ),
};
export default {
    state,
    getters,
    actions,
    mutations,
}
