require('./bootstrap');

window.Vue = require('vue').default;

import router from './router'
import App from './components/App.vue'

import Vue from 'vue';
import VueTables from 'vue-tables-2';

Vue.use(VueTables.ClientTable);


const app = new Vue({
    el: '#app',
    router,
    render: h => h(App)
});
