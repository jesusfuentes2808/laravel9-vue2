import axios from 'axios';
import storage from '../storage';

const state = {
    invoiceList: [],
    invoice: null,
    errorInvoiceCreate: {submit: false},
};

const getters = {
    invoice: state => state.invoice,
    invoiceList: state => state.invoiceList,
    errorInvoiceCreate: state => state.errorInvoiceCreate,
};

const actions = {
    async fetchInvoices({commit, rootState}){
        let config = {
            headers: {
                Authorization: "Bearer " + rootState.UsersModule.token.token,
            }
        };

        const response = await axios.get(route("api.invoice.get"),  config);
        commit("setInvoiceList", response.data.data);
    },

    async saveInvoice({commit, rootState}, data){
        let config = {
            headers: {
                Authorization: "Bearer " + rootState.UsersModule.token.token,
            }
        };

        await axios.post(route("api.invoice.store"),  data, config).then((response) => {
            commit("setErrorInvoiceCreate", {submit: true, response: response.data})
        }).catch((error) => {
            console.log(error.response.data);
            commit("setErrorInvoiceCreate", {submit: true, response: error.response.data});
        });

        //commit("setInvoices", response.data.data);
    },

    async updateInvoice({commit, rootState}, data){
        let config = {
            headers: {
                Authorization: "Bearer " + rootState.UsersModule.token.token,
            }
        };

        await axios.put(route("api.invoice.update"),  data, config).then((response) => {
            commit("setErrorInvoiceCreate", {submit: true, response: response.data})
        }).catch((error) => {
            console.log(error.response.data);
            commit("setErrorInvoiceCreate", {submit: true, response: error.response.data});
        });

        //commit("setInvoices", response.data.data);
    },


    async getByIdInvoice({commit, rootState}, id){
        let config = {
            headers: {
                Authorization: "Bearer " + rootState.UsersModule.token.token,
            }
        };

        const response = await axios.get(route("api.invoice.getById", {id: id}),  config);
        commit("setInvoice", response.data.data);
    },

    async resetErrorInvoiceCreate({commit}){
        commit("setErrorInvoiceCreate", {submit: false});
    }

};

const mutations = {
    setInvoiceList: (state, invoiceList) => (
        state.invoiceList = invoiceList
    ),

    setInvoice: (state, invoice) => (
        state.invoice = invoice
    ),

    setErrorInvoiceCreate: (state, invoiceList) => (
        state.errorInvoiceCreate = invoiceList
    ),

};
export default {
    state,
    getters,
    actions,
    mutations,
}
