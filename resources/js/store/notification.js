const state = {
    notification: ''
}

const mutations = {
    setNotification(state, message) {
        state.notification = message;
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