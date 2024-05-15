const state = {
    notification: ''
}

const mutations = {
    clearNotification(state) {
        state.notification = null;
    }
}

const actions = {
    showNotification({ commit }, message) {
        commit('setNotification', message);
    }
}

export default {
    state,
    mutations,
    actions
}