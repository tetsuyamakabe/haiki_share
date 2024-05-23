<template>
    <main class="l-main">
        <section class="l-main__wrapper">
            <h1 class="c-title u-mb__xl">利用者パスワード変更</h1>
            <form @submit.prevent="resetPassword" class="c-form">

                <!-- バリデーションエラーメッセージ -->
                <span v-if="errors && errors.oldPassword" class="c-error u-mt__s u-mb__s">{{ errors.oldPassword[0] }}</span>
                <span v-if="errors && errors.newPassword" class="c-error u-mt__s u-mb__s">{{ errors.newPassword[0] }}</span>
                <span v-if="errors && errors.password_confirmation" class="c-error u-mt__s u-mb__s">{{ errors.password_confirmation[0] }}</span>

                <!-- 古いパスワード -->
                <label for="old_password" class="c-label">古いパスワード</label>
                <div class="c-input__password">
                    <input v-model="formData.oldPassword" id="old_password" :type="OldPasswordType" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.oldPassword }" placeholder="英数字8文字以上で入力してください">
                    <span @click="togglePasswordVisibility('old_password')"><i :class="OldPasswordIconClass"></i></span>
                </div>
                <!-- 新しいパスワード -->
                <label for="new_password" class="c-label">新しいパスワード</label>
                <div class="c-input__password">
                    <input v-model="formData.newPassword" id="new_password" :type="NewPasswordType" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.newPassword }" placeholder="英数字8文字以上で入力してください">
                    <span @click="togglePasswordVisibility('new_password')"><i :class="NewPasswordIconClass"></i></span>
                </div>
                <!-- 新しいパスワード（再入力） -->
                <label for="password-confirm" class="c-label">新しいパスワード（再入力）</label>
                <div class="c-input__password">
                    <input v-model="formData.password_confirmation" id="password-confirm" :type="PasswordConfirmType" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.password_confirmation }" placeholder="英数字8文字以上で入力してください">
                    <span @click="togglePasswordVisibility('password_confirm')"><i :class="PasswordConfirmIconClass"></i></span>
                </div>

                <input type="hidden" name="token" v-model="token">
                <input type="hidden" name="email" v-model="email">

                <!-- メール送信ボタン -->
                <button type="submit" class="c-button c-button__submit c-button__convenience u-pd__s u-mt__m">パスワードを変更する</button>

            </form>
        </section>
        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
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
            errors: null,
            OldPasswordType: 'password', // 古いパスワードの初期設定
            NewPasswordType: 'password', // 新しいパスワードの初期設定
            PasswordConfirmType: 'password', // パスワード（再入力）の初期設定
            OldPasswordIconClass: 'far fa-eye-slash', // 古いパスワードの初期アイコン
            NewPasswordIconClass: 'far fa-eye-slash', // 新しいパスワードの初期アイコン
            PasswordConfirmIconClass: 'far fa-eye-slash', // 新しいパスワード（再入力）の初期アイコン
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
        },

        // パスワードの表示・非表示を切り替えるメソッド
        togglePasswordVisibility(type) {
            if (type === 'old_password') {
                this.OldPasswordType = this.OldPasswordType === 'password' ? 'text' : 'password';
                this.OldPasswordIconClass = this.OldPasswordIconClass === 'far fa-eye-slash' ? 'far fa-eye' : 'far fa-eye-slash';
            } else if (type === 'new_password') {
                this.NewPasswordType = this.NewPasswordType === 'password' ? 'text' : 'password';
                this.NewPasswordIconClass = this.NewPasswordIconClass === 'far fa-eye-slash' ? 'far fa-eye' : 'far fa-eye-slash';
            } else {
                this.PasswordConfirmType = this.PasswordConfirmType === 'password' ? 'text' : 'password';
                this.PasswordConfirmIconClass = this.PasswordConfirmIconClass === 'far fa-eye-slash' ? 'far fa-eye' : 'far fa-eye-slash';
            }
        }
    }
};
</script>