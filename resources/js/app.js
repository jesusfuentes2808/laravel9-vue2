import './bootstrap';
import Vue from 'vue';
import VueRouter from 'vue-router';
import routes from './routes';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import Vuex from 'vuex';
import store from './store'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(VueRouter);

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
//Vue.use(Vuex);

//Vue.component('welcome-component', require('./component/WelcomeComponent').default);
Vue.component('vue-select', require('vue-select2').default);

import '../css/app.scss';

const router = new VueRouter({
    routes,
    mode: "history"
});

router.beforeEach(async (to, from, next) => {
    await store.dispatch('getToken');
    const isAuthenticated = store.getters['token'];
    if ((to.name !== 'login' && to.name !== 'register') && !isAuthenticated) next({ name: 'login' })
    else if (to.name === 'login' && isAuthenticated) next({name: 'admin.invoice'})
    else next();
});

const app = new Vue({
    el: "#app",
    router,
    store,
    mounted(){
        console.log("APP");
    }
});
