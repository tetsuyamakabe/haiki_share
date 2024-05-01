import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        userId: "",
        token: "",
        role: ""
    },

    mutations: {
        updateUser(state, user) {
            state.userId = user.user_id;
            state.token = user.token;
            state.role = user.role;
        }
    },

    actions: {
        auth(context, user) {
            context.commit('updateUser', user);
        }
    },

    modules: {},
})

export default store;