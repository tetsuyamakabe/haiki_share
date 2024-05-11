const state = {
    user: null,
}
  
const getters = {
    check: state => !! state.user,
    username: state => state.user ? state.user.name : '',
    icon: state => state.user ? `/storage/icons/${state.user.icon}` : '',
    role: state => state.user ? state.user.role : '',
    id: state => state.user ? state.user.id : '',
}

const mutations = {
    setUser (state, user) {
        state.user = user
    },

    clearUser(state) {
        state.user = null;
    }
};

const actions = {
    async currentUser (context) {
        const response = await axios.get('/api/user');
        const user = response.data || null;
        context.commit('setUser', user);
    }
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}