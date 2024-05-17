<template>
    <main class="l-main">
        <div class="l-main__user">
            <section class="l-main__wrapper">
                <h1 class="c-title u-mb__xl">利用者ログイン</h1>
                <form @submit.prevent="submitForm" class="c-form">

                    <!-- バリデーションエラーメッセージ -->
                    <span v-if="errors && errors.email" class="c-error">{{ errors.email[0] }}</span>
                    <span v-if="errors && errors.password" class="c-error">{{ errors.password[0] }}</span>

                    <!-- メールアドレス -->
                    <label for="email" class="c-label">メールアドレス</label>
                    <input v-model="formData.email" id="email" type="email" class="c-input" :class="{ 'is-invalid': errors && errors.email }" autocomplete="email">
                    <!-- パスワード -->
                    <label for="password" class="c-label">パスワード</label>
                    <div class="c-input__password">
                        <input v-model="formData.password" id="password" :type="PasswordType" class="c-input" :class="{ 'is-invalid': errors && errors.password }" placeholder="英数字8文字以上で入力してください">
                        <span @click="togglePasswordVisibility('password')"><i :class="PasswordIconClass"></i></span>
                    </div>

                    <!-- パスワード保持 -->
                    <div class="c-checkbox c-checkbox__container u-mt__m u-mb__m">
                        <input class="c-checkbox u-mr__s" type="checkbox" v-model="remember" id="remember">
                        <span class="c-text" for="remember">パスワードを保持する</span>
                    </div>

                    <!-- パスワードリマインダー -->
                    <a class="c-link" href="/user/password/email">パスワードをお忘れの場合はこちら</a>

                    <!-- ログインボタン -->
                    <button type="submit" class="c-button c-button__submit c-button__user u-mt__m">ログインする</button>
                </form>
            </section>
        </div>
        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
    </main>
</template>
  
<script>
import axios from '../../axiosErrorHandler';

export default {
    data() {
        return {
            formData: {
                email: '',
                password: '',
            },
            remember: false, // パスワード保持にチェックしたか
            errors: null,
            PasswordType: 'password', // パスワードの初期設定
            PasswordConfirmType: 'password', // パスワード（再入力）の初期設定
            PasswordIconClass: 'far fa-eye-slash', // 初期アイコン
            PasswordConfirmIconClass: 'far fa-eye-slash', // 初期アイコン
        };
    },

    methods: {
        // 入力された値をサーバー側に送信するメソッド
        async submitForm() {
            try {
                const response = await axios.post('/api/user/login', this.formData);
                console.log('ログインします。');
                console.log('APIからのレスポンス:', response.data);
                await this.$store.dispatch('auth/currentUser'); // ログイン状態を保持
                this.$router.push({ name: 'user.mypage' }); // ログイン後、マイページに遷移
            } catch (error) {
                console.log('errorは、', error);
                console.error('ログイン失敗:', error.response.data);
                this.errors = error.response.data.errors;
            }
        },

        // パスワードの表示・非表示を切り替えるメソッド
        togglePasswordVisibility(type) {
            if (type === 'password') {
                this.PasswordType = this.PasswordType === 'password' ? 'text' : 'password';
                this.PasswordIconClass = this.PasswordIconClass === 'far fa-eye-slash' ? 'far fa-eye' : 'far fa-eye-slash';
            } else if (type === 'password_confirm') {
                this.PasswordConfirmType = this.PasswordConfirmType === 'password' ? 'text' : 'password';
                this.PasswordConfirmIconClass = this.PasswordConfirmIconClass === 'far fa-eye-slash' ? 'far fa-eye' : 'far fa-eye-slash';
            }
        }
    }
};
</script>