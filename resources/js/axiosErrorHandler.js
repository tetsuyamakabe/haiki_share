import axios from 'axios';
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
                redirectToLoginPage();
                throw new CustomError('Unauthorized', 401); // HTTP 401 Unauthorized
        }
    }
);

export default axios;