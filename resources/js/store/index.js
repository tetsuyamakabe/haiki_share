import Vue from 'vue'
import Vuex from 'vuex'

import auth from './auth'
import notification from './notification'


Vue.use(Vuex)

const store = new Vuex.Store({
    modules: {
        auth,
        notification
    }
})

export default store