import './bootstrap'
import Vue from 'vue'
import router from './router'
import store from './store'
import App from './App.vue'
import axiosErrorHandler from './axiosErrorHandler'

const createApp = async () => {
    await store.dispatch('auth/currentUser')
    Vue.prototype.axios = axiosErrorHandler

    new Vue({
        el: '#app',
        router,
        store,
        components: { App },
        template: '<App />'
    })
}

createApp()