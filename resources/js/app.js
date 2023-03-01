require('./bootstrap');

window.Vue = require('vue').default;

import router from './router'
import App from './components/App.vue'

const app = new Vue({
    el: '#app',
    router,
    render: h => h(App)
});
