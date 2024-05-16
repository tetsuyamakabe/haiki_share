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
                router.push('/home');
            break;
            case 404:
                router.push('/404');
            break;
            case 500:
                router.push('/500');
            break;
            case 422:
                this.errors = error.response.data.errors;
            break;
        }
    }
);

export default axios;