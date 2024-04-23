<template>
    <main class="l-main">
        <div class="p-register">
            <div class="p-register__form">
                <h1 class="c-title">パスワード変更メール送信</h1>
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
                                <input v-model="formData.oldPassword" id="old_password" class="c-input" :class="{ 'is-invalid': errors && errors.oldPassword }" placeholder="英数字8文字以上で入力してください">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="new_password" class="c-label">新しいパスワード</label></th>
                            <td>
                                <input v-model="formData.newPassword" id="new_password" class="c-input" :class="{ 'is-invalid': errors && errors.newPassword }" placeholder="英数字8文字以上で入力してください">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="password-confirm" class="c-label">新しいパスワード（再入力）</label></th>
                            <td>
                                <input v-model="formData.password_confirmation" id="password_confirm" class="c-input" :class="{ 'is-invalid': errors && errors.password_confirmation }" placeholder="英数字8文字以上で入力してください">
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
        </div>
    </main>
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
        // URLからトークンを取得
        this.token = this.$route.params.token;
        this.email = this.$route.query.email;
        console.log('emailは、', this.email); 
        console.log('tokenは、', this.token);
    },

    methods: {
        // パスワードリセット処理
        resetPassword() {
            // トークンを含めたデータを作成
            const data = {
                email: this.email,
                token: this.token,
                oldPassword: this.formData.oldPassword,
                newPassword: this.formData.newPassword,
                password_confirmation: this.formData.password_confirmation,
            };

            axios.post('/api/convenience/password/reset', data).then(response => {
                this.message = response.data.message;
                console.log('this.messageは、', this.message);
                console.log('パスワードを変更します。');
                this.$router.push({ name: 'convenience.login' }); // パスワード変更後、ログイン画面に遷移
            }).catch(error => {
                console.error('パスワード変更失敗:', error.response.data);
                this.errors = error.response.data.errors;
            });
        }
    }
};
</script>