import Vue from 'vue'
import Vuex from 'vuex'
import UsersModule from "./store/modules/users-module";
import InvoicesModule from "./store/modules/invoices-module";
import ProductsModule from "./store/modules/products-module";
import axios from "axios";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        iva: 0,
    },
    getters: {
        iva: state => state.iva,
    },
    actions: {
        async setIVA({commit}){
            console.log("GETIVA");
            const response = await axios.get(route('api.iva.get'));
            console.log(response.data.data.iva);
            commit('setIVA', response.data.data.iva);
        },
    },
    mutations: {
        setIVA(state, iva){
            state.iva = iva;
        }
    },

    modules: {
        UsersModule,
        InvoicesModule,
        ProductsModule,
    },
    namespaced: true,
});
