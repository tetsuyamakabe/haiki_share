<template>
    <main class="l-main">
        <section class="l-main__wrapper">
            <h1 class="c-title u-mb__xl">利用者パスワード変更</h1>
            <form @submit.prevent="resetPassword" class="c-form">

                <!-- 古いパスワード -->
                <label for="old_password" class="c-label">古いパスワード<span class="c-required">必須</span></label>
                <span v-if="errors && errors.oldPassword" class="c-error u-mt__s">{{ errors.oldPassword[0] }}</span>
                <div class="c-input__password">
                    <input v-model="formData.oldPassword" id="old_password" :type="OldPasswordType" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.oldPassword }" placeholder="英数字8文字以上で入力してください">
                    <span @click="togglePasswordVisibility('old_password')"><i :class="OldPasswordIconClass"></i></span>
                </div>

                <!-- 新しいパスワード -->
                <label for="new_password" class="c-label">新しいパスワード<span class="c-required">必須</span></label>
                <span v-if="errors && errors.newPassword" class="c-error u-mt__s">{{ errors.newPassword[0] }}</span>
                <div class="c-input__password">
                    <input v-model="formData.newPassword" id="new_password" :type="NewPasswordType" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.newPassword }" placeholder="英数字8文字以上で入力してください">
                    <span @click="togglePasswordVisibility('new_password')"><i :class="NewPasswordIconClass"></i></span>
                </div>

                <!-- 新しいパスワード（再入力） -->
                <label for="password-confirm" class="c-label">新しいパスワード（再入力）<span class="c-required">必須</span></label>
                <span v-if="errors && errors.password_confirmation" class="c-error u-mt__s">{{ errors.password_confirmation[0] }}</span>
                <div class="c-input__password">
                    <input v-model="formData.password_confirmation" id="password-confirm" :type="PasswordConfirmType" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.password_confirmation }" placeholder="英数字8文字以上で入力してください">
                    <span @click="togglePasswordVisibility('password_confirm')"><i :class="PasswordConfirmIconClass"></i></span>
                </div>

                <!-- パスワード再設定のトークンとメールアドレスの隠しフィールド -->
                <input type="hidden" name="token" v-model="token">
                <input type="hidden" name="email" v-model="email">

                <!-- メール送信ボタン -->
                <button type="submit" class="c-button c-button__submit c-button__user u-pd__s u-mt__m">パスワードを変更する</button>

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
                oldPassword: '', // 古いパスワード
                newPassword: '', // 新しいパスワード
                password_confirmation: '', // パスワード（再入力）
            },
            token: '', // トークン
            email: '', // メールアドレス
            errors: null, // エラーメッセージ
            OldPasswordType: 'password', // 古いパスワードの初期設定
            NewPasswordType: 'password', // 新しいパスワードの初期設定
            PasswordConfirmType: 'password', // パスワード（再入力）の初期設定
            OldPasswordIconClass: 'far fa-eye-slash', // 古いパスワードの初期アイコン
            NewPasswordIconClass: 'far fa-eye-slash', // 新しいパスワードの初期アイコン
            PasswordConfirmIconClass: 'far fa-eye-slash', // 新しいパスワード（再入力）の初期アイコン
        }
    },

    mounted() {
        // URLからトークンとメールアドレスを取得
        this.token = this.$route.params.token;
        this.email = this.$route.query.email;
        console.log('emailは、', this.email); 
        console.log('tokenは、', this.token);
    },

    methods: {
        // パスワードリセット処理
        resetPassword() {
            // トークン・メールアドレスを含めたデータを作成
            const requestData = {
                token: this.token, // トークン
                email: this.email, // メールアドレス
                oldPassword: this.formData.oldPassword, // 古いパスワード
                newPassword: this.formData.newPassword, // 新しいパスワード
                password_confirmation: this.formData.password_confirmation, // パスワード（再入力）
            };
            // 利用者パスワード変更APIをPOST送信
            axios.post('/api/user/password/reset', requestData).then(response => { // トークンとメールアドレスを含めたデータを含むリクエスト
                this.message = response.data.message;
                console.log('this.messageは、', this.message);
                console.log('パスワードを変更します。');
                this.$router.push({ name: 'user.login' }); // パスワード変更後、ログイン画面に遷移
            }).catch(error => {
                console.error('パスワード変更失敗:', error.response.data);
                this.errors = error.response.data.errors;
            });
        },

        // パスワードの表示・非表示を切り替えるメソッド
        togglePasswordVisibility(type) {
            if (type === 'old_password') { // 古いパスワードの入力フォーム
                this.OldPasswordType = this.OldPasswordType === 'password' ? 'text' : 'password'; // PasswordTypeによってパスワードの表示・非表示を切り替え
                this.OldPasswordIconClass = this.OldPasswordIconClass === 'far fa-eye-slash' ? 'far fa-eye' : 'far fa-eye-slash'; // PasswordIconClassによってパスワードのアイコンを切り替え
            } else if (type === 'new_password') { // 新しいパスワードの入力フォーム
                this.NewPasswordType = this.NewPasswordType === 'password' ? 'text' : 'password'; // PasswordTypeによってパスワードの表示・非表示を切り替え
                this.NewPasswordIconClass = this.NewPasswordIconClass === 'far fa-eye-slash' ? 'far fa-eye' : 'far fa-eye-slash'; // PasswordIconClassによってパスワードのアイコンを切り替え
            } else { // パスワード（再入力）の入力フォーム
                this.PasswordConfirmType = this.PasswordConfirmType === 'password' ? 'text' : 'password'; // PasswordTypeによってパスワードの表示・非表示を切り替え
                this.PasswordConfirmIconClass = this.PasswordConfirmIconClass === 'far fa-eye-slash' ? 'far fa-eye' : 'far fa-eye-slash'; // PasswordIconClassによってパスワードのアイコンを切り替え
            }
        }
    }
};
</script>