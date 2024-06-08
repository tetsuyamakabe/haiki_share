const state = {
    flashMessage: {
      message: '',
      type: ''
    }
},

mutations = {
    SET_FLASH_MESSAGE(state, payload) {
        state.flashMessage.message = payload.message;
        state.flashMessage.type = payload.type;
    },

    CLEAR_FLASH_MESSAGE(state) {
        state.flashMessage.message = '';
        state.flashMessage.type = '';
    }
},

actions = {
    setFlashMessage({ commit }, payload) {
        commit('SET_FLASH_MESSAGE', payload);
        setTimeout(() => {
            commit('CLEAR_FLASH_MESSAGE');
        }, 3000);
    }
},

getters = {
    flashMessage: state => state.flashMessage
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}