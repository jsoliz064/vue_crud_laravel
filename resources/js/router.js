import Vue from 'vue'
import VueRouter from 'vue-router'

import index from './components/productos/index.vue'
import create from './components/productos/create.vue'

Vue.use(VueRouter)

const routes = [
    {
        path: '/index',
        component: index
    },
    {
        name: 'create',
        path: '/create',
        component: create,
    }
    
]

const router = new VueRouter({
    mode: 'history',
    routes: routes
})

export default router
