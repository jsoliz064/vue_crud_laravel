import Vue from 'vue'
import VueRouter from 'vue-router'

import productosIndex from './components/productos/index.vue'
import takePhoto from './components/takePhoto/takePhoto.vue'


Vue.use(VueRouter)

const routes = [
    {
        path: '/index',
        component: productosIndex
    },
    {
        name: 'takePhoto',
        path: '/takePhoto',
        component: takePhoto,
    }
    
]

const router = new VueRouter({
    mode: 'history',
    routes: routes
})

export default router
