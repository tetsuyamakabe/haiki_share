import axios from 'axios';
import store from './store';
import CustomError from './CustomError';

// リダイレクト先の定義
const redirectToLoginPage = () => {
    window.location.href = '/home';
};

// レスポンスのinterceptorを設定
axios.interceptors.response.use(
    function (response) {
        // 成功時の処理
        return response;
    }, 

    function (error) {
        // 失敗時の処理
        switch (error.response?.status) {
            case 401:
                store.dispatch('showNotification', 'ログインが必要です。');
                redirectToLoginPage();
                throw new CustomError('Unauthorized', 401); // HTTP 401 Unauthorized
            case 403:
                store.dispatch('showNotification', 'エラーが発生しました。しばらく経ってからやり直してください。');
                throw new CustomError('Forbidden', 403); // HTTP 403 Forbidden
            case 404:
                store.dispatch('showNotification', 'お探しのページは見つかりませんでした。');
                throw new CustomError('Not found', 404); // HTTP 403 Not Found
            case 500:
                store.dispatch('showNotification', 'エラーが発生しました。しばらく経ってからやり直してください。');
                throw new CustomError('Internal server error', 500); // HTTP 500 Internal server error
            default:
                store.dispatch('showNotification', '予期せぬエラーが発生しました。しばらく経ってからやり直してください。');
                throw new CustomError('An error occurred', error.response?.status); // その他のエラー
        }
    }
);

export default axios;