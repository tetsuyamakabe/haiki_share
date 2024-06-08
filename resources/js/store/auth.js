const state = {
    user: null, // ユーザー情報の初期状態
}
  
const getters = {
    check: state => !! state.user, // 認証チェック
    username: state => state.user ? state.user.name : '', // ユーザー名
    avatar: state => state.user ? `${state.user.avatar}` : 'https://haikishare.com/avatar/default.png', // 顔写真
    role: state => state.user ? state.user.role : '', // role
    id: state => state.user ? state.user.id : '', // ユーザーID
}

const mutations = {
    setUser (state, user) {
        state.user = user // ユーザー情報をuserに入れる
    },

    clearUser(state) {
        state.user = null; // ユーザー情報をnullにする
    }
};

const actions = {
    async currentUser (context) {
        const response = await axios.get('/api/user'); // API経由で現在ユーザー情報を取得
        const user = response.data || null; // APIからのレスポンスデータをuserに入れる
        context.commit('setUser', user); // 取得したユーザー情報をsetUserミューテーションでストアにコミット
    }
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}