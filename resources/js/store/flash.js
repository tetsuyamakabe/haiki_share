const state = {
    flashMessage: {
        message: '', // フラッシュメッセージ
        type: '' // フラッシュメッセージのタイプ（成功時かエラー時か）
    }
},

mutations = {
    // フラッシュメッセージのメッセージとタイプをstateに設定する
    SET_FLASH_MESSAGE(state, payload) {
        state.flashMessage.message = payload.message;
        state.flashMessage.type = payload.type;
    },
    // フラッシュメッセージのメッセージとタイプをクリアする
    CLEAR_FLASH_MESSAGE(state) {
        state.flashMessage.message = '';
        state.flashMessage.type = '';
    }
},

actions = {
    // SET_FLASH_MESSAGEミューテーション後、5秒後にCLEAR_FLASH_MESSAGEミューテーションを実行する（5秒間、フラッシュメッセージを表示する）
    setFlashMessage({ commit }, payload) {
        commit('SET_FLASH_MESSAGE', payload);
        setTimeout(() => {
            commit('CLEAR_FLASH_MESSAGE');
        }, 5000);
    }
},

getters = {
    flashMessage: state => state.flashMessage // フラッシュメッセージの取得
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}