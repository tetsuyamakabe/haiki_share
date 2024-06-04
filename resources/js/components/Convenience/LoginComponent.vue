<template>
    <main class="l-main">
        <section class="l-main__wrapper">
            <h1 class="c-title u-mt__xl u-mb__xl">コンビニログイン</h1>
            <form @submit.prevent="submitForm" class="c-form">

                <!-- メールアドレス -->
                <label for="email" class="c-label">メールアドレス<span class="c-required">必須</span></label>
                <span v-if="errors && errors.email" class="c-error u-mt__s">{{ errors.email[0] }}</span>
                <input v-model="formData.email" id="email" type="text" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.email }" autocomplete="email">
                <!-- パスワード -->
                <label for="password" class="c-label">パスワード<span class="c-required">必須</span></label>
                <span class="c-text c-text__note">※パスワードは、半角数字・英字大文字・小文字、記号（!@#$%^&*）を使って8文字以上で入力してください</span>
                <span v-if="errors && errors.password" class="c-error u-mt__s">{{ errors.password[0] }}</span>
                <div class="c-input__password">
                    <input v-model="formData.password" id="password" :type="PasswordType" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.password }" placeholder="8文字以上で入力してください">
                    <span @click="togglePasswordVisibility('password')"><i :class="PasswordIconClass"></i></span>
                </div>

                <!-- 次回のログインを省略する -->
                <div class="c-checkbox c-checkbox__container u-mt__m u-mb__m">
                    <input class="c-checkbox u-mr__s" type="checkbox" v-model="remember" id="remember">
                    <span class="c-text" for="remember">次回のログインを省略する</span>
                </div>

                <!-- パスワードリマインダー -->
                <a class="c-link" href="/convenience/password/email">パスワードをお忘れの場合はこちら</a>

                <!-- ログインボタン -->
                <button type="submit" class="c-button c-button__submit c-button__main u-pd__s u-mt__m">ログインする</button>

            </form>
        </section>
        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
    </main>
</template>
  
<script>
import axios from 'axios';

export default {
    data() {
        return {
            formData: {
                email: '', // メールアドレス
                password: '', // パスワード
            },
            remember: false, // 次回ログインの省略にチェックしたか
            errors: null, // エラーメッセージ
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
                // コンビニログインAPIをPOST送信
                await axios.post('/api/convenience/login', {...this.formData, remember: this.remember}); // 入力値と次回ログイン省略のrememberを含むリクエスト
                await this.$store.dispatch('auth/currentUser'); // ログイン状態を保持
                this.$router.push({ name: 'convenience.mypage' }); // ログイン後、マイページに遷移
            } catch (error) {
                console.error('ログイン失敗:', error.response.data);
                this.errors = error.response.data.errors;
            }
        },

        // パスワードの表示・非表示を切り替えるメソッド
        togglePasswordVisibility(type) {
            if (type === 'password') { // パスワードの入力フォーム
                this.PasswordType = this.PasswordType === 'password' ? 'text' : 'password'; // PasswordTypeによってパスワードの表示・非表示を切り替え
                this.PasswordIconClass = this.PasswordIconClass === 'far fa-eye-slash' ? 'far fa-eye' : 'far fa-eye-slash'; // PasswordIconClassによってパスワードのアイコンを切り替え
            } else if (type === 'password_confirm') { // パスワード（再入力）の入力フォーム
                this.PasswordConfirmType = this.PasswordConfirmType === 'password' ? 'text' : 'password'; // PasswordTypeによってパスワードの表示・非表示を切り替え
                this.PasswordConfirmIconClass = this.PasswordConfirmIconClass === 'far fa-eye-slash' ? 'far fa-eye' : 'far fa-eye-slash'; // PasswordIconClassによってパスワードのアイコンを切り替え
            }
        }
    }
};
</script>