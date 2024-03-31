<template>
    <div class="c-container">
        <form @submit.prevent="resetPassword">

            <!-- バリデーションエラーメッセージ -->
            <span v-if="errors && errors.oldPassword" class="c-error">{{ errors.oldPassword[0] }}</span>
            <span v-if="errors && errors.newPassword" class="c-error">{{ errors.newPassword[0] }}</span>
            <span v-if="errors && errors.password_confirmation" class="c-error">{{ errors.password_confirmation[0] }}</span>

            <!-- 【TODO】 type="pasword"を検証のため削除しているので追加 -->
            <table>
                <tr>
                    <th><label for="old_password" class="c-label">古いパスワード</label></th>
                    <td>
                        <input v-model="formData.oldPassword" id="old_password" class="c-input" :class="{ 'is-invalid': errors && errors.oldPassword }" autocomplete="password" placeholder="英数字8文字以上で入力してください">
                    </td>
                </tr>
                <tr>
                    <th><label for="new_password" class="c-label">新しいパスワード</label></th>
                    <td>
                        <input v-model="formData.newPassword" id="new_password" class="c-input" :class="{ 'is-invalid': errors && errors.newPassword }" autocomplete="password" placeholder="英数字8文字以上で入力してください">
                    </td>
                </tr>
                <tr>
                    <th><label for="password-confirm" class="c-label">新しいパスワード（再入力）</label></th>
                    <td>
                        <input v-model="formData.password_confirmation" id="password_confirm" class="c-input" :class="{ 'is-invalid': errors && errors.password_confirmation }" autocomplete="password" placeholder="英数字8文字以上で入力してください">
                    </td>
                </tr>
            </table>

            <input type="hidden" name="token" v-model="token">
            <input type="hidden" name="email" v-model="email">

            <div class="p-resetpassword__button">
                <button type="submit" class="c-button">変更する</button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            formData: {
                oldPassword: '',
                newPassword: '',
                password_confirmation: '',
            },
            token: '',
            email: '',
            errors: null
        }
    },

    mounted() {
        //  【TODO】 Vue Routerの $route オブジェクトを使ってパラメータを取得を検討
        // URLからトークンを取得
        const path = window.location.pathname;
        const parts = path.split('/');
        this.token = parts[parts.length - 1];
        const params = new URLSearchParams(window.location.search);
        this.email = params.get('email');
        console.log('email:', this.email); 
        console.log('token:', this.token);
    },

    methods: {
        // パスワードリセット処理
        resetPassword() {
            // 新しいパスワードと新しいパスワード（再入力）のバリデーション
            if (this.formData.newPassword !== this.formData.password_confirmation) {
                this.error = "新しいパスワードと新しいパスワード（再入力）が合っていません。";
                return;
            }

            // トークンを含めたデータを作成
            const data = {
                email: this.email,
                token: this.token,
                old_password: this.formData.oldPassword,
                new_password: this.formData.newPassword,
                password_confirmation: this.formData.password_confirmation,
            };

            axios.post('/user/password/reset', data).then(response => {
                // パスワードがリセットされた後にログイン画面に遷移する
                window.location.href = '/user/login';
            }).catch(error => {
                console.log('フロントエンドのデータは、', data);
                console.error('パスワード変更失敗:', error.response.data);
                this.errors = error.response.data.errors;
            });
        }
    }
};
</script>