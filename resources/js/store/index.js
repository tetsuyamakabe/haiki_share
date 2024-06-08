import Vue from 'vue'
import Vuex from 'vuex'

import auth from './auth'
import flash from './flash'

Vue.use(Vuex)

const store = new Vuex.Store({
    modules: {
        auth, 
        flash
    }
})

export default store