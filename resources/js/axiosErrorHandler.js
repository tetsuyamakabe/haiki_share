import axios from 'axios';
import router from './router';

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
                router.push('/top'); // 401エラーの場合はTOPページに遷移
                break;
            case 404:
                router.push('/404'); // 404エラーの場合は404ページに遷移
                break;
            case 500:
                router.push('/500'); // 500エラーの場合は500ページに遷移
                break;
            default:
                // 未定義のエラーコードの場合はPromise.reject()を返す
                return Promise.reject(error);
        }
    }
);

export default axios;